<?php

namespace App\Conversation\Commands;

use App\Entity\State;
use App\Message;
use App\User;

class General
{
    protected $triggers =
        [
          CreateCommand::class,
          TimeCommand::class,
          ListCommand::class,
          StartCommand::class
        ];

    public function run(User $user, Message $message, State $state)
    {
        foreach ($this->triggers as $trigger) {

            /** @var AbstractCommand $command */
            $command = new $trigger($user, $message, $state);

            if ($command->hasCommand($state)) {
                $state = $command->start();

                return $state;
            } elseif ($command->hasTrigger($message->text)) {
                $state = $command->handle();

                return $state;
            }

        }

        $getCommand = new GetCommand($user, $message, $state);
        $state = $getCommand->handle();

        return $state;
    }

    public function getTriggers()
    {
        return $this->triggers;
    }
}