<?php

namespace App\Conversation\Answers\Command;

use App\Conversation\Answers\AbstractAnswer;
use App\Entity\State;
use Telegram\Bot\Keyboard\Keyboard;

class CommandFinishCreate extends AbstractAnswer
{
    protected $validation;
    protected $state;

    public function __construct(State $state = null)
    {
        $this->state = $state;
    }

    public function answer()
    {
        $return = [
            'text' => 'Command successful created! Use ' . $this->state->getUserCommand(),
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
        return $state;
    }


    protected function getRules()
    {
        return ['finish' => 'required'];
    }
}