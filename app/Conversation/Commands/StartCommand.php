<?php

namespace App\Conversation\Commands;

use App\Command;
use App\Conversation\Answers\Command\CommandStart;
use App\Conversation\SendMessage;
use App\Entity\State;
use App\Message;
use App\User;

class StartCommand extends AbstractCommand implements ICommand
{
    protected $triggers = [
        '/start',
        '/hello',
    ];

    public function triggerAction(User $user, Message $message) : State
    {
        $general = new General();

        $triggers = $general->getTriggers();

        $array = [];

        foreach ($triggers as $trigger) {
            /** @var AbstractCommand $obj */
            $obj = new $trigger();
            $array = array_merge($array, array_slice($obj->getTriggers(), 0 , 1));
        }

        $answer = new CommandStart($array);

        $this->messenger->addMessage($answer->answer());

        return $this->getNewStateForTriggerAction($user);
    }
}