<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogTransaction extends Model
{
    protected $table = 'log_transactions';

    protected $fillable = ['amount', 'user_id', 'type'];
    
}
