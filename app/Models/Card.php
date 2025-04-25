<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date', 'is_due_checked', 'status', 'img', 'parent_id', 'completed_at'];

    protected $casts = [
        'is_due_checked' => 'boolean',
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function parentList()
    {
        return $this->belongsTo(ParentList::class, 'parent_id');
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class, 'card_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($card) {
            // $originalStatus = $card->getOriginal('status');
            $card->checkAndUpdateStatus();

            // Simpan ke DB jika status berubah
            if ($card->isDirty('status')) {
                $card->saveQuietly(); // Simpan tanpa memicu event
            }
        });

        static::updating(function ($card) {
            $card->checkAndUpdateStatus();
        });

        static::creating(function ($card) {
            $card->checkAndUpdateStatus();
        });
    }

    public function checkAndUpdateStatus()
    {
        // Jika sudah completed, pertahankan statusnya
        if ($this->is_due_checked && $this->status !== 'completed') {
            $this->status = 'completed';
            $this->completed_at = $this->completed_at ?: now();
            return;
        }

        // Jika belum completed, cek due date
        if (!$this->is_due_checked) {
            if ($this->due_date && now() > $this->due_date) {
                $this->status = 'late';
            } else {
                $this->status = 'due';
            }
        }
    }
}
