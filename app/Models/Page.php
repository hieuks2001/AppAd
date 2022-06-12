<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    // Page for traffic model
    protected $table = 'pages';

    protected $primaryKey = 'id';

    protected $fillable = [
        'page_name', 'page_image'
    ];

}
