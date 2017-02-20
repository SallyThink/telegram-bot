<?php

namespace App;

class Message extends \Eloquent
{
    protected $fillable = ['telegram_id', 'message_id', 'message'];


    /**
     * @param \Telegram\Bot\Objects\Message $message
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(\Telegram\Bot\Objects\Message $message)
    {
        return $this->create(
            [
                'telegram_id' => $message->getFrom()->getId(),
                'message_id' => $message->getMessageId(),
                'message' => $message->getText()
            ]
        );
    }
}
