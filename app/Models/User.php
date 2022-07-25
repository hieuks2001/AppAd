<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use Notifiable, Uuids;

  protected $fillable = [
    'password', 'username', 'wallet', 'commission', 'is_admin', 'status', 'mission_count', 'mission_attempts', 'user_type_id'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
  ];

  // Cast onsite into array
  protected $casts = [
    'mission_count' => 'array'
  ];

  /**
   * The model's default values for attributes.
   *
   * @var array
   */
  protected $attributes = [
    'mission_count' => '[]',
  ];

  protected $primaryKey = 'id';
  protected $table = 'user_missions';


  // Set incrementing to False -> Custom primary key -> Not return 0 when using Eloquent Laravel model
  public $incrementing = false;

  public $timestamps = true;

  public function userType()
  {
    return $this->belongsTo('App\Models\UserType');
  }
}
