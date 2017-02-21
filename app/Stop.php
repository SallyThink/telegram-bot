<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    protected $fillable = ['type', 'number', 'route', 'stops'];

    protected $casts = ['stops' => 'json'];

    public function getStop($vars)
    {
        return $this->where($vars)->get()->first();
    }
}
