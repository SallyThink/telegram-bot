<?php

namespace App\Conversation\Answers;

use App\Conversation\CheckWay;
use App\Conversation\Helpers\TimeHelper;
use App\Entity\State;
use Telegram\Bot\Keyboard\Keyboard;

class Time extends AbstractAnswer
{
    protected $time;

    public function __construct(State $state)
    {
        $allTime = CheckWay::getTime($state);

        $helper = new TimeHelper();

        $this->time = implode(PHP_EOL, $helper->getNextTime($allTime));
    }

    public function answer() : array
    {
        $return = [
            'parse_mode' => 'HTML',
            'text' => '<pre>' . $this->time . '</pre>',
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
    public function setParam(State $state, $val) : State
    {
        $state->setTime($val);

        return $state;
    }

    protected function getRules()
    {
        return ['time' => 'required'];
    }
}