<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
// Mission model => Complete mission for reward
{
  use Uuids;

  protected $table = 'missions';

  protected $primaryKey = 'id';

  protected $fillable = [
    'user_id', 'page_id', 'reward', 'status', 'ip', 'user_agent'
  ];

  /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'updated_at' => null,
    ];

    // Tell eloquent not auto insert updated_at
    const UPDATED_AT = null;

  // Set incrementing to False -> Custom primary key -> Not return 0 when using Eloquent Laravel model
  public $incrementing = false;
}
