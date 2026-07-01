<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    public $timestamps = false;
    protected $fillable = ['userid', 'name', 'email', 'role', 'status', 'note', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
