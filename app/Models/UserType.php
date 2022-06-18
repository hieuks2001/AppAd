<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use Uuids;

    protected $fillable = [
        'name', 'max_traffic'
    ];

    protected $primaryKey = 'id';

    protected $table = 'user_types';

    // Set incrementing to False -> Custom primary key -> Not return 0 when using Eloquent Laravel model
    public $incrementing = false;

    public $timestamps = false;
}
