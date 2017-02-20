<?php

namespace App\Conversation\Answers;

use App\Entity\State;
use App\Message;
use Illuminate\Validation\Validator;
use Telegram\Bot\Keyboard\Keyboard;

abstract class AbstractAnswer
{
    public function __construct(){}

    abstract public function answer($userId);

    /**
     * @param Message $message
     * @return Validator
     */
    abstract public function validation(Message $message);

    /**
     * @param Validator $validator
     * @param $userId
     *
     * @return array
     */
    public function sendError(Validator $validator, $userId)
    {
        $return = [
            'chat_id' => $userId,
            'text' => $validator->errors()->first(),
            'reply_markup' => Keyboard::hide()
            ];

        return $return;
    }
}