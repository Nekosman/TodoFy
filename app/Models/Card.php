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
        'completed_at'
    ];

    protected $casts = [
        'is_due_checked' => 'boolean',
        'due_date' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function parentList()
    {
        return $this->belongsTo(ParentList::class, 'parent_id');
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class, 'card_id');
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function($card) {
            if ($card->isDirty('is_due_checked') && $card->is_due_checked) {
                $card->completed_at = now();
            }
        });
    }

}
