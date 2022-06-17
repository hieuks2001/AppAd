<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Uuids;

class Page extends Model
{
    use Uuids;
    // Page for traffic model
    protected $table = 'pages';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_uuid', 'keyword', 'image', 'url', 'traffic_per_day', 'traffic_sum', 'onsite', 'status', 'price', 'price_per_traffic', 'traffic_remain'
    ];

    // Set incrementing to False -> Custom primary key -> Not return 0 when using Eloquent Laravel model
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
