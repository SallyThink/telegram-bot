<?php

namespace App\Conversation\Answers;

use App\Entity\State;
use Telegram\Bot\Keyboard\Keyboard;

class CommandTime extends AbstractAnswer
{

    public function answer()
    {
        $return = [
            'text' => 'Check time',
            'reply_markup' => Keyboard::make([
                'keyboard' => [
                    ['Last'],
                    ['Next']
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
        $state->setTime($val);

        return $state;
    }


    protected function getRules()
    {
        return ['continue' => 'required|in:Last,Next'];
    }
}