<?php

namespace App;

class Message extends \Eloquent
{
    protected $fillable = ['chat_id', 'message_id', 'text'];

    public function __contruct()
    {
    }

    public function store(\Telegram\Bot\Objects\Message $message)
    {
        return $this->create(
            [
                'chat_id' => $message->getFrom()->getId(),
                'message_id' => $message->getMessageId(),
                'text' => $message->getText()
            ]
        );
    }
}
