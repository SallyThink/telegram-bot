<?php

namespace App\Conversation\Answers;

use App\Conversation\CheckWay;
use App\Conversation\Helpers\TimeHelper;
use App\Entity\State;
use Telegram\Bot\Keyboard\Keyboard;

class Time extends AbstractAnswer
{
    protected $time;
    protected $emptyTimeSentence = 'Walk by yourself';

    public function __construct(State $state)
    {
        $allTime = CheckWay::getTime($state);

        $helper = new TimeHelper();

        $time = implode(PHP_EOL, $helper->getNextTime($allTime));

        $this->time = $time == null ? $this->emptyTimeSentence : $time;
    }

    public function answer() : array
    {
        $return = [
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