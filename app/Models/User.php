<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'password', 'username', 'wallet', 'commission'
    ];

        /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $primaryKey = 'user_uuid';
    protected $table = 'users';

    
    // Set incrementing to False -> Custom primary key -> Not return 0 when using Eloquent Laravel model
    public $incrementing = false;

    public $timestamps = true;
    
}
