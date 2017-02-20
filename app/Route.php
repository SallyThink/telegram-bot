<?php

namespace App;


class Route extends \Eloquent
{
    protected $fillable = ['type', 'number', 'routes'];
    protected $casts = ['routes' => 'json'];
}
