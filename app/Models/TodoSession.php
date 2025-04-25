<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'img',
        'title',
        'description',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parentLists()
    {
        return $this->hasMany(ParentList::class, 'session_id');
    }
}
