<?php

namespace App\Conversation\Commands;

use App\Command;
use App\Conversation\Answers\Command\CommandList;
use App\Conversation\SendMessage;
use App\Entity\State;
use App\Message;
use App\User;

class ListCommand extends AbstractCommand implements ICommand
{
    protected $triggers = [
        '/commands',
        '/mycommands',
        '/custom',
        '/customcommands',
        '/my',
    ];

    public function triggerAction(User $user, Message $message) : State
    {
        $state = $this->getNewStateForTriggerAction($user);

        $answer = new CommandList($state);

        $this->messenger->addMessage($answer->answer());

        return $state;
    }
}