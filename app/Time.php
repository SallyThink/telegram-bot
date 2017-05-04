<?php

namespace App;


class Time extends \Eloquent
{
    protected $fillable = ['type', 'number', 'route', 'stop', 'isWeekend', 'time'];
    protected $casts = ['time' => 'json'];

    public function getTime($vars)
    {
        return $this->where($vars)->get()->first();
    }

    public function test()
    {
        return [];
    }
}
