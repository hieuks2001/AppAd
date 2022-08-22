<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    //
    use Uuids;
    protected $fillable = [
      'keys', 'code'
    ];
    //
    protected $table = 'codes';
    protected $primaryKey = 'id';

    public $incrementing = false;
}
