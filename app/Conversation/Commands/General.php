<?php

namespace App\Conversation\Commands;

use App\Entity\State;
use App\Message;
use App\User;

class General
{
    protected $triggers =
        [
          CreateCommand::class => '/create',
          TimeCommand::class => '/gettime',
        ];

    public function __construct()
    {
    }

    public function run(User $user, Message $message, State $state)
    {
        if ($state->getCommand() != '') {
            $create = new CreateCommand($user, $message, $state);
            $state = $create->creating();
            return $state;
        }

        foreach ($this->triggers as $class => $trigger) {
            if ($trigger === $message->text) {
                /** @var ICommand $command */
                $command = new $class($user, $message, $state);
                $state = $command->handle();
                return $state;
            }
        }

        $getCommand = new GetCommand($user, $message, $state);

        $state = $getCommand->handle();

        return $state;
    }
}