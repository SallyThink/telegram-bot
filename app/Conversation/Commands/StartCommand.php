<?php

namespace App\Conversation\Commands;

use App\Command;
use App\Conversation\SendMessage;
use App\Entity\State;

class StartCommand extends AbstractCommand implements ICommand
{
    protected $triggers = [
        '/start',
        '/hello',
    ];

    public function handle() : State
    {
        $text = '';

        $triggers = new General();

        foreach ($triggers->getTriggers() as $trigger) {
            $text .= $trigger . "\n";
        }

        SendMessage::getInstance()->addMessage([
            'parse_mode' => 'HTML',
            'text' => $text,
        ]);

        return $this->state;
    }
}