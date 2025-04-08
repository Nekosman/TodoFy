<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CheckList;
use DB;
use App\Models\ParentList;
use App\Models\TodoSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class TodoController extends Controller
{
    /**
     * Menampilkan semua session milik user
     */
    public function index()
    {
        $sessions = TodoSession::where('user_id', auth()->id())->get();
        return view('pages.dashboard', compact('sessions'));
    }

    //session function
    public function storeSession(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'visibility' => 'required|in:private,public',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        return DB::transaction(function () use ($request) {
            if ($request->hasFile('img')) {
                $imageName = time() . '.' . $request->img->extension();
                $request->img->move(public_path('storage/images'), $imageName);
            } else {
                $imageName = null;
            }

            // Simpan sesi ke database
            $session = TodoSession::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'description' => $request->description,
                'img' => $imageName ? 'storage/images/' . $imageName : null, // Jika tidak ada gambar, tetap null
                'visibility' => $request->visibility,
            ]);

            // Buat parent list default
            $defaultLists = ['To Do', 'Doing', 'Done'];
            foreach ($defaultLists as $listName) {
                ParentList::create([
                    'session_id' => $session->id,
                    'title' => $listName,
                ]);
            }

            // Ambil ulang data session dengan relasi parentLists
            $todoSession = TodoSession::with('parentLists')->findOrFail($session->id);

            return redirect()->route('projects.show', ['id' => $todoSession->id]);
        });
    }

    public function show($id)
    {
        $todoSession = TodoSession::with('parentLists')->findOrFail($id);
        return view('pages.projectSession', compact('todoSession'));
    }

    public function detail(TodoSession $TodoSession)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Project',
            'data' => [
                'id' => $TodoSession->id,
                'title' => $TodoSession->title,
                'img' => $TodoSession->img,
                'description' => $TodoSession->description,
                'visibility' => $TodoSession->visibility,
            ],
        ]);
    }

    public function updateSession(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'visibility' => 'required|in:private,public',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $session = TodoSession::findOrFail($id);

            // Handle image upload jika ada file baru
            if ($request->hasFile('img')) {
                // Hapus gambar lama jika ada
                if ($session->img && file_exists(public_path($session->img))) {
                    unlink(public_path($session->img));
                }

                $imageName = time() . '.' . $request->img->extension();
                $request->img->move(public_path('storage/images'), $imageName);
                $imgPath = 'storage/images/' . $imageName;
            } else {
                // Pertahankan gambar lama jika tidak ada upload baru
                $imgPath = $session->img;
            }

            $session->update([
                'title' => $request->title,
                'description' => $request->description,
                'visibility' => $request->visibility,
                'img' => $imgPath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Session updated successfully.',
                'data' => $session,
            ]);
        });
    }

    public function deleteSession($id)
    {
        DB::transaction(function () use ($id) {
            // Dapatkan session yang akan dihapus
            $session = TodoSession::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Hapus semua data terkait
            $session->parentLists()->each(function ($parentList) {
                // Hapus cards di setiap parent list
                $parentList->cards()->each(function ($card) {
                    // Hapus checklist di setiap card
                    $card->checklists()->delete();
                    // Hapus card
                    $card->delete();
                });
                // Hapus parent list
                $parentList->delete();
            });

            // Hapus session itu sendiri
            $session->delete();
        });

        return response()->json(['message' => 'Session and all related data deleted successfully']);
    }

    //parent function
    public function storeParent(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:todo_sessions,id', // Perbaikan nama tabel
        ]);

        $defaultTitle = 'New List'; // Judul default yang lebih deskriptif

        $parentList = ParentList::create([
            'session_id' => $request->session_id, // Gunakan session_id bukan id
            'title' => $defaultTitle,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Parent list created successfully',
            'data' => $parentList, // Kembalikan data yang baru dibuat
        ]);
    }

    public function detailParent(ParentList $ParentList)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Project',
            'data' => [
                'id' => $ParentList->id,
                'title' => $ParentList->title,
            ],
        ]);
    }

    public function updateParent(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $parentList = ParentList::findOrFail($id);

        if ($parentList->title === $request->title) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No changes detected.',
                ],
                400,
            );
        }

        $parentList->update($request->only('title'));

        return response()->json([
            'success' => true,
            'message' => 'Parent list updated successfully.',
            'data' => $parentList,
        ]);
    }

    public function deleteParent($id)
    {
        try {
            // Dapatkan semua session_id yang dimiliki user
            $userSessionIds = TodoSession::where('user_id', auth()->id())
                ->pluck('id')
                ->toArray();

            // Cari parent list yang dimiliki user
            $parentList = ParentList::where('id', $id)->whereIn('session_id', $userSessionIds)->first();

            if (!$parentList) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Parent list not found or you dont have permission to delete it',
                    ],
                    404,
                );
            }

            // Hapus parent list (cards akan terhapus otomatis karena onDelete cascade)
            $parentList->delete();

            return response()->json([
                'success' => true,
                'message' => 'Parent list and all related cards deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error deleting parent list: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    //card function
    public function storeCard(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date',
                'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'parent_id' => 'required|exists:parent_lists,id',
            ]);

            return DB::transaction(function () use ($request, $validated) {
                // Tambahkan $validated di sini
                if ($request->hasFile('img')) {
                    $imageName = time() . '.' . $request->img->extension();
                    $request->img->move(public_path('storage/images/cards'), $imageName);
                    $validated['img'] = $imageName; // Tambahkan nama gambar ke data yang divalidasi
                }

                $card = Card::create($validated);

                return response()->json([
                    'success' => true,
                    'message' => 'Card created successfully',
                    'card' => $card,
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to create card: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function showCard(Card $card)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Project',
            'data' => [
                'id' => $card->id,
                'title' => $card->title,
                'description' => $card->description,
                'due_date' => $card->due_date,
                'is_due_checked' => $card->is_due_checked,
                'img' => $card->img,
            ],
        ]);
    }

    public function updateCard(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date',
                'is_due_checked' => 'boolean',
                'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            ]);

            return DB::transaction(function () use ($request, $validated, $id) {
                $card = Card::findOrFail($id);

                // Tambahkan $validated di sini
                if ($request->hasFile('img')) {
                    $imageName = time() . '.' . $request->img->extension();
                    $request->img->move(public_path('storage/images/cards'), $imageName);
                    $validated['img'] = $imageName; // Tambahkan nama gambar ke data yang divalidasi
                }

                $card->update($validated);

                return response()->json([
                    'success' => true,
                    'message' => 'Card created successfully',
                    'card' => $card->fresh(),
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to create card: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    /**
     * Menghapus card
     */
    public function deleteCard($id)
    {
        try {
            $card = Card::find($id);

            if (!$card) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'card not found',
                    ],
                    404,
                );
            }

            if ($card->img) {
                $imagePath = public_path('storage/images/cards/' . $card->img);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Hapus parent list (cards akan terhapus otomatis karena onDelete cascade)
            $card->delete();

            return response()->json([
                'success' => true,
                'message' => 'card deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error deleting parent list: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }
}
