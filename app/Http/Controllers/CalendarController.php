<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\ParentList;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function events(Request $request)
    {
        $events = Card::with(['parentList.todoSession'])
            ->whereHas('parentList.todoSession', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->whereNotNull('due_date')
            ->get()
            ->map(function ($card) {
                return [
                    'id' => $card->id,
                    'title' => $card->title,
                    'start' => $card->due_date,
                    'color' => $this->getStatusColor($card->status),
                    'extendedProps' => [
                        'parent_list' => $card->parentList->title ?? 'No List',
                        'session' => $card->parentList->todoSession->title ?? 'No Session'
                    ],
                    'url' => route('cards.show', $card->id)
                ];
            });

        return response()->json($events);
    }

    protected function getStatusColor($status)
    {
        return match($status) {
            'completed' => '#10B981', // hijau
            'late' => '#EF4444',      // merah
            'user' => '#3B82F6',      // biru untuk user
            'computer' => '#8B5CF6',  // ungu untuk computer
            'take' => '#F59E0B',      // kuning untuk take
            default => '#6B7280',     // abu-abu default
        };
    }

    public function dailyCards($date)
    {
        $cards = Card::with(['parentList.todoSession'])
            ->whereHas('parentList.todoSession', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->whereDate('due_date', $date)
            ->orderBy('due_date')
            ->get();

        return view('cards.daily', compact('cards', 'date'));
    }
}