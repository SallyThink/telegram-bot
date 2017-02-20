<?php

namespace App\Conversation\Answers;

use App\Entity\State;
use App\Message;
use App\Parser\Minsktrans;
use Illuminate\Validation\Rule;
use Telegram\Bot\Keyboard\Keyboard;

class Stop extends AbstractAnswer
{
    protected $allStops;
    protected $validation;

    public function __construct(State $state)
    {
        $minsktrans = new Minsktrans($state->getType(), $state->getNumber());

        $this->validation = $minsktrans->getAllStops($state->getRoute());

        foreach($this->validation as $v)
        {
            $this->allStops[] = [$v];
        }
    }

    public function answer($userId)
    {

        $return = [
            'chat_id' => $userId,
            'text' => 'Check',
            'reply_markup' => Keyboard::make([
                'keyboard' => $this->allStops,
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
        $state->setStop($val);

        return $state;
    }

    /**
     * @param Message $message
     * @return \Illuminate\Validation\Validator
     */
    public function validation(Message $message)
    {
        $validation = \Validator::make(['stop' => $message->message] , ['stop' => ['required', Rule::in($this->validation)]]);

        return $validation;
    }
}