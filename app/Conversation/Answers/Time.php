<?php

namespace App\Conversation\Answers;

use App\Conversation\CheckWay;
use App\Entity\State;
use App\Message;
use App\Parser\Main;
use Carbon\Carbon;
use Telegram\Bot\Keyboard\Keyboard;

class Time extends AbstractAnswer
{
    protected $time;

    public function __construct(State $state)
    {
        $allTime = CheckWay::getTime($state);

        $now = Carbon::now('Europe/Minsk');
        $hour = $now->hour;

        if (isset($allTime[$hour])) {
            $this->time = $hour . ':' . str_replace(' ', ' ' . $hour . ':', $allTime[$hour]);
            if ($now->minute > 30 && isset($allTime[++$hour]))
            {
                $this->time .= PHP_EOL . $hour . ':' . str_replace(' ', ' ' . $hour . ':', $allTime[$hour]);
            }
        } else {
            $this->time = 'no time';
        }
    }

    public function answer()
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
    public function setParam(State $state, $val)
    {
        $state->setTime($val);

        return $state;
    }

    protected function getRules()
    {
        return ['time' => 'required'];
    }
}