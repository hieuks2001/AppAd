<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
  protected $fillable = ['user_id', 'user', 'otp'];

  protected $casts = [
    'expire' => 'datetime',
  ];
}
