<?php

namespace App\Conversation\Answers;

use App\Conversation\CheckWay;
use App\Entity\State;
use App\Exceptions\ParserException;
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
        $this->finalStops = CheckWay::getRoutes($state);
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

    protected function getRules()
    {
        return ['route' => ['required', Rule::in($this->finalStops)]];
    }
}