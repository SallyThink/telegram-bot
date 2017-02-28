<?php

namespace App\Conversation\Answers;

use App\Entity\State;
use Telegram\Bot\Keyboard\Keyboard;

class ContinueCreate extends AbstractAnswer
{

    public function answer()
    {
        $return = [
            'text' => 'want more?',
            'reply_markup' => Keyboard::make([
                'keyboard' => [
                    ['Yes'],
                    ['No']
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
        if ('Yes' == $val){
            $state->setState(CommandName::class);
        }

        return $state;
    }


    protected function getRules()
    {
        return ['continue' => 'required|in:Yes,No'];
    }
}