<?php

namespace App\Conversation\Commands;

use App\Conversation\Helpers\TimeHelper;
use App\Conversation\SendMessage;
use App\Entity\State;

class TimeCommand extends AbstractCommand implements ICommand
{
    public function handle() : State
    {
        $helper = new TimeHelper();

        SendMessage::getInstance()->addMessage([
            'text' => $helper->getTime()
        ]);

        return $this->state;
    }
}