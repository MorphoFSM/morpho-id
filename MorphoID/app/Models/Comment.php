<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['specimen_id', 'nama', 'role', 'comment', 'likes'];

    public function specimen()
    {
        return $this->belongsTo(Specimen::class);
    }
}
