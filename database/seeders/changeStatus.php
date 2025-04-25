<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class changeStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Untuk update data existing
        Card::where('is_due_checked', true)->update([
            'status' => 'completed',
            'completed_at' => DB::raw('updated_at'),
        ]);

        Card::where('is_due_checked', false)
            ->where('due_date', '<', now())
            ->update(['status' => 'late']);

        Card::whereNull('status')->update(['status' => 'due']);
    }
}
