<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    protected $fillable = [
        'username',
        'role',
        'ip_address',
        'user_agent',
        'login_time',
        'status',
    ];

    public $timestamps = true;
}
