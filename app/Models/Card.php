<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description',
        'due_date',
        'is_due_checked',
        'img',
        'parent_id',
    ];

    public function parentList()
    {
        return $this->belongsTo(ParentList::class, 'parent_id');
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class, 'card_id');
    }
}
