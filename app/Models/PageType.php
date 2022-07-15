<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageType extends Model
{
    // Set incrementing to False -> Custom primary key -> Not return 0 when using Eloquent Laravel model
    public $incrementing = false;

    protected $fillable = [
        'name', 'onsite',
    ];

    // Cast onsite into array
    protected $casts = [
        'onsite' => 'array'
    ];

    protected $primaryKey = 'id';

    protected $table = 'page_types';
}
