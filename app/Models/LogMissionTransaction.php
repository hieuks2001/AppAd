<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogMissionTransaction extends Model
{
    protected $table = 'log_mission_transactions';

    protected $fillable = ['amount', 'user_id', 'type', 'before', 'after', 'status'];

}
