<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogTransaction extends Model
{
    protected $table = 'log_mission_transactions';

    protected $fillable = ['amount', 'user_id', 'from_user_id' , 'type', 'status', 'created_at'];

}
