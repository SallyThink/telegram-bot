<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    protected $fillable = ['type', 'number', 'route', 'stops'];

    protected $casts = ['stops' => 'json'];
}
