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
    'user_uuid', 'keyword', 'image', 'url', 'traffic_per_day',
    'traffic_sum', 'onsite', 'status', 'price', 'price_per_traffic',
    'traffic_remain', 'page_type_id', 'timeout', 'hold_percentage'
  ];

  // Set incrementing to False -> Custom primary key -> Not return 0 when using Eloquent Laravel model
  public $incrementing = false;

  protected $dates = [
    'timeout',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'timeout' => 'timestamp',
  ];

  protected $hidden = [
    'status', 'traffic_sum', 'traffic_remain',
    'page_type_id', 'hold_percentage', 'price',
    'price_per_traffic', 'traffic_per_day', 'timeout',
    'user_id', 'created_at', 'updated_at', 'priority', 'note'
  ];

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }

  public function pageType()
  {
    return $this->belongsTo('App\Models\PageType');
  }
}
