<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    // Page for traffic model
    protected $table = 'pages';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_uuid', 'keyword', 'image', 'url', 'traffic_per_day', 'traffic_sum', 'onsite', 'is_approved', 'status'
    ];
}
