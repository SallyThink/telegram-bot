<?php

namespace App;


class Route extends \Eloquent
{
    protected $fillable = ['type', 'number', 'routes'];
    protected $casts = ['routes' => 'json'];

    /**
     * @param array $vars
     * @return \Eloquent|null
     */
    public function checkRoute(array $vars)
    {
        return $this->where($vars)->get()->first();
    }
}
