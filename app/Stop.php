<?php

namespace App;


class Stop extends \Eloquent
{
    protected $fillable = ['type', 'number', 'route', 'stops'];

    protected $casts = ['stops' => 'json'];

    /**
     * @param array $vars
     * @return \Eloquent|null
     */
    public function getStop($vars)
    {
        return $this->where($vars)->get()->first();
    }

    public function getStops()
    {
        return [];
    }
}
