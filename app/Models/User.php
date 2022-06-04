<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'password', 'username', 'wallet', 'commission'
    ];
    protected $primaryKey = 'user_uuid';
    protected $table = 'users';
}
