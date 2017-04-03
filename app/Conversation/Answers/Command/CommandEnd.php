<?php

namespace App\Conversation\Answers\Command;

use App\Conversation\Answers\AbstractAnswer;
use App\Entity\State;

class CommandEnd extends AbstractAnswer
{
    public function __construct(State $state)
    {
        $this->state = $state;
    }

    /**
     * @return array
     */
    public function answer()
    {
        return ['text' => 'Command successfully deleted'];
    }

    /**
     * @param State $state
     * @param string|int $val
     * @return State
     */
    public function setParam(State $state, $val)
    {
        return $state;
    }

    /**
     * @return array
     */
    protected function getRules()
    {
        return [];
    }
}