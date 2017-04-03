<?php

namespace App\Conversation\Answers\Command;

use App\Conversation\Answers\AbstractAnswer;
use App\Entity\State;

class CommandStart extends AbstractAnswer
{
    protected $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function answer()
    {
        return ['text' => implode(PHP_EOL, $this->text)];
    }

    public function setParam(State $state, $val)
    {
        return $state;
    }

    protected function getRules()
    {
        return [];
    }
}