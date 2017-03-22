<?php

namespace App\Conversation\Answers;

use App\Entity\State;
use App\Message;
use Telegram\Bot\Keyboard\Keyboard;

class Type extends AbstractAnswer
{
    public function answer()
    {
        $return = ['text' => 'Check', 'reply_markup' => Keyboard::make()
            ->row(Keyboard::button(['text'=>'Автобус']))
            ->row(Keyboard::button(['text'=>'Троллейбус']), Keyboard::button(['text'=>'Трамвай']))
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
        $state->setType($this->translate($val));

        return $state;
    }

    protected function translate(string $type)
    {
        switch($type) {
            case 'Автобус':
                return 'Autobus';
            case 'Троллейбус':
                return 'Trolleybus';
            case 'Трамвай':
                return 'Tramway';
        }
        return $type;
    }

    protected function getRules()
    {
        return ['transport' => 'required|in:Автобус,Троллейбус,Трамвай'];
    }
}