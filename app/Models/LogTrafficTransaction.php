<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class LogTrafficTransaction extends Model
{
  use Notifiable;

  protected $table = 'log_traffic_transactions';

  protected $fillable = ['amount', 'user_id', 'type', 'before', 'after', 'status'];
}
