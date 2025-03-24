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

    /**
     * Membuat session baru dan parent list default
     */

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
    /**
     * Menghapus session dan semua isinya
     */
    public function deleteSession($id)
    {
        TodoSession::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();
        return response()->json(['message' => 'Session deleted successfully']);
    }

    /**
     * Menambahkan card ke parent list
     */
    public function storeCard(Request $request)
    {
        $request->validate(['parent_list_id' => 'required|exists:parent_lists,id', 'title' => 'required|string']);

        $card = Card::create(['parent_list_id' => $request->parent_list_id, 'title' => $request->title]);
        return response()->json($card);
    }

    /**
     * Menghapus card
     */
    public function deleteCard($id)
    {
        Card::destroy($id);
        return response()->json(['message' => 'Card deleted successfully']);
    }

    /**
     * Menambahkan checklist ke card
     */
    public function storeChecklist(Request $request)
    {
        $request->validate(['card_id' => 'required|exists:cards,id', 'title' => 'required|string']);

        $checklist = Checklist::create(['card_id' => $request->card_id, 'title' => $request->title, 'completed' => false]);
        return response()->json($checklist);
    }

    /**
     * Mengubah status checklist (selesai/tidak)
     */
    public function toggleChecklist($id)
    {
        $checklist = Checklist::findOrFail($id);
        $checklist->completed = !$checklist->completed;
        $checklist->save();

        return response()->json($checklist);
    }

    /**
     * Menghapus checklist
     */
    public function deleteChecklist($id)
    {
        Checklist::destroy($id);
        return response()->json(['message' => 'Checklist deleted successfully']);
    }
}
