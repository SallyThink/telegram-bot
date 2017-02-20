<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    protected $fillable = ['type', 'number', 'route', 'stop', 'time'];

    protected $casts = ['time' => 'json'];
}
