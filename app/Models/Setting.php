<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    protected $fillable = [
      'name', 'value'
    ];
    //
    protected $table = 'settings';
    protected $primaryKey = 'id';
}
