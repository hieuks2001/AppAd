<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Missions extends Model
{
    protected $fillable = [
        'ms_name', 'ms_code', 'ms_userUUID', 'ms_price','ms_status'
    ];
    protected $primaryKey = 'id';
    protected $table = 'missions';
    protected $connection = 'mysql';


}

