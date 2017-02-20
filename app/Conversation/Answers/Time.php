<?php

namespace App\Conversation\Answers;

use App\Entity\State;
use App\Message;
use App\Parser\Minsktrans;
use Carbon\Carbon;
use Telegram\Bot\Keyboard\Keyboard;

class Time extends AbstractAnswer
{
    protected $time;

    public function __construct(State $state)
    {
        $minsktrans = new Minsktrans($state->getType(), $state->getNumber());
        $allTime = $minsktrans->getTime($state->getRoute(), $state->getStop());

        $now = Carbon::now('Europe/Minsk');
        $hour = $now->hour;

        $this->time = $hour . ':' . str_replace(' ', ' ' . $hour . ':', $allTime[$hour]);
        if($now->minute > 30)
        {
            $this->time .= PHP_EOL . ++$hour . ':' . str_replace(' ', ' ' . $hour . ':', $allTime[$hour]);
        }
    }

    public function answer($userId)
    {
        $return = [
            'chat_id' => $userId,
            'parse_mode' => 'HTML',
            'text' => '<pre>' . $this->time . '</pre>',
            'reply_markup' => Keyboard::hide()
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

    /**
     * @param Message $message
     * @return \Illuminate\Validation\Validator
     */
    public function validation(Message $message)
    {
        $validation = \Validator::make(['time' => $message->message] , ['time' => 'required']);

        return $validation;
    }
}