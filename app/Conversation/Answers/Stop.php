<?php

namespace App\Conversation\Answers;

use App\Conversation\CheckWay;
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
        $this->validation = CheckWay::getStops($state);

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

    protected function getRules()
    {
        return ['stop' => ['required', Rule::in($this->validation)]];
    }
}