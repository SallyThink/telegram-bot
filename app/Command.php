<?php

namespace App;


class Command extends \Eloquent
{
    protected $fillable = ['chat_id', 'command', 'data'];
    protected $casts = ['data' => 'json'];

    public function getCommand($userId, $message)
    {
        return $this->where('chat_id', $userId)->where('command', $message)->get()->first();
    }
}
