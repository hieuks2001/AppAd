<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogTrafficTransaction extends Model
{
    protected $table = 'log_traffic_transactions';

    protected $fillable = ['amount', 'user_id', 'type', ];

}
