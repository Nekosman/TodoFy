<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'session_id'
    ];

    public function todoSession(){
        return $this->belongsTo(TodoSession::class, 'session_id');
    }

    public function cards(){
        return $this->hasMany(Card::class, 'parent_id');
    }
}
