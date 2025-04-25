<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class FilterController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            $calendarEvents = $this->getCalendarEvents();
            return view('pages.cardlist', compact('calendarEvents'));
        }

        $filter = $request->input('filter', 'all');
        
        $query = Card::with('parentList.todoSession')
            ->whereHas('parentList.todoSession', function ($q) {
                $q->where('user_id', Auth::id());
            });

        switch ($filter) {
            case 'due': $query->where('status', 'due'); break;
            case 'completed': $query->where('status', 'completed'); break;
            case 'late': $query->where('status', 'late'); break;
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('parent_list', function ($card) {
                return $card->parentList->title ?? 'No List';
            })
            ->addColumn('formatted_due_date', function ($card) {
                return $card->due_date?->format('Y-m-d') ?? '';
            })
            ->addColumn('status_badge', function ($card) {
                $classes = [
                    'due' => 'bg-yellow-100 text-yellow-800',
                    'completed' => 'bg-green-100 text-green-800',
                    'late' => 'bg-red-100 text-red-800',
                ];
                return '<span class="px-2 py-1 rounded-full text-xs '.($classes[$card->status] ?? '').'">'.ucfirst($card->status).'</span>';
            })
            ->addColumn('action', function ($card) {
                // Perbaikan: Cek parentList dan session_id sebelum membuat route
                $sessionId = optional($card->parentList)->session_id;
                return $sessionId 
                    ? '<a href="'.route('projects.show', $sessionId).'" class="text-blue-500 hover:text-blue-700">Edit</a>'
                    : '<span class="text-gray-400">No Session</span>';
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    protected function getCalendarEvents()
    {
        return Card::with(['parentList.todoSession'])
            ->whereHas('parentList.todoSession', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->whereNotNull('due_date')
            ->get()
            ->map(function ($card) {
                // Perbaikan: Handle null parentList atau todoSession
                $sessionId = optional($card->parentList)->session_id;
                
                return [
                    'title' => $card->title,
                    'start' => $card->due_date,
                    'color' => $this->getEventColor($card->status),
                    'url' => $sessionId ? route('projects.show', $sessionId) : '#',
                    'extendedProps' => [
                        'status' => $card->status,
                        'parent_list' => optional($card->parentList)->title ?? 'No List'
                    ]
                ];
            });
    }

    protected function getEventColor($status)
    {
        return match($status) {
            'completed' => '#10B981', // hijau
            'late' => '#EF4444',      // merah
            default => '#3B82F6',     // biru
        };
    }
}