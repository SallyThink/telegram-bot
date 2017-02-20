<?php

namespace App\Conversation\Answers;

use App\Entity\State;
use App\Message;
use Telegram\Bot\Keyboard\Keyboard;

class Number extends AbstractAnswer
{

    public function answer($userId)
    {
        $return = [
            'chat_id' => $userId,
            'text' => 'Введите номер',
            'reply_markup' => Keyboard::hide()
        ];

        return $return;
    }

    /**
     * @param State $state
     * @param $val
     * @return State
     */
    public function setParam(State $state, $val)
    {
        $state->setNumber($val);

        return $state;
    }

    /**
     * @param Message $message
     * @return \Illuminate\Validation\Validator
     */
    public function validation(Message $message)
    {
        $validation = \Validator::make(['number' => $message->message] , ['number' => 'required|integer']);

        return $validation;
    }
}