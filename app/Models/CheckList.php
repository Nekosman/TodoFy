<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'is_checklist_checked', 'card_id'
    ];

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }
}
