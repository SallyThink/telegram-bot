<?php

namespace App\Conversation\Answers;

use App\Entity\State;
use App\Message;
use Telegram\Bot\Keyboard\Keyboard;

class Type extends AbstractAnswer
{

    public function answer($userId)
    {
      $return = [
          'chat_id' => $userId,
          'text' => 'Check',
          'reply_markup' => Keyboard::make([
              'keyboard' => [
                  ['Автобус'],
                  ['Троллейбус', 'Трамвай']
              ],
              'resize_keyboard' => false,
              'one_time_keyboard' => false,
          ])
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
        $state->setType($val);

        return $state;
    }

    /**
     * @param Message $message
     * @return \Illuminate\Validation\Validator
     */
    public function validation(Message $message)
    {
        $validation = \Validator::make(['transport' => $message->message] , ['transport' => 'required|in:Автобус,Троллейбус,Трамвай']);

        return $validation;
    }
}