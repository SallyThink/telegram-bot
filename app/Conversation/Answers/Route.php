<?php

namespace App\Conversation\Answers;

use App\Entity\State;
use App\Message;
use App\Parser\FinalStops;
use App\Parser\Minsktrans;
use Illuminate\Validation\Rule;
use Telegram\Bot\Keyboard\Keyboard;

class Route extends AbstractAnswer
{
    protected $finalStops;

    public function __construct(State $state)
    {
        $minstrans = new Minsktrans($state->getType(), $state->getNumber());

        $this->finalStops = $minstrans->getFinalStops();
    }

    public function answer($userId)
    {
        $return = [
            'chat_id' => $userId,
            'text' => 'Check Route',
            'reply_markup' => Keyboard::make([
                'keyboard' => [
                    [$this->finalStops[0]],
                    [$this->finalStops[1]],
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
        $state->setRoute($val);

        return $state;
    }

    /**
     * @param Message $message
     * @return \Illuminate\Validation\Validator
     */
    public function validation(Message $message)
    {
        $validation = \Validator::make(['route' => $message->message] , ['route' => ['required', Rule::in($this->finalStops)]]);

        return $validation;
    }
}