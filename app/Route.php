<?php

namespace App;


class Route extends \Eloquent
{
    protected $fillable = ['type', 'number', 'routes'];
    protected $casts = ['routes' => 'json'];

    public function checkRoute($vars)
    {
        return $this->where($vars)->get()->first();
    }
}
