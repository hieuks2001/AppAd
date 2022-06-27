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
        'password', 'username', 'wallet', 'commission', 'is_admin', 'status', 'mission_count'
    ];

        /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $primaryKey = 'id';
    protected $table = 'users';

    
    // Set incrementing to False -> Custom primary key -> Not return 0 when using Eloquent Laravel model
    public $incrementing = false;

    public $timestamps = true;

    public function pageType(){
        return $this->belongsTo('App\Models\PageType');
    }
    
}
