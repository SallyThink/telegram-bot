<?php

namespace App\Conversation\Commands;

use App\Conversation\Helpers\TimeHelper;
use App\Conversation\SendMessage;
use App\Entity\State;
use App\Message;
use App\User;

class TimeCommand extends AbstractCommand implements ICommand
{
    protected $triggers = [
        '/time',
        '/gettime',
        '/now',
    ];

    public function triggerAction(User $user, Message $message) : State
    {
        $helper = new TimeHelper();

        $this->messenger->addMessage([
            'text' => $helper->getTime()
        ]);

        return $this->getNewStateForTriggerAction($user);
    }
}