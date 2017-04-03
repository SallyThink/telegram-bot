<?php

namespace App\Conversation\Commands;

use App\Conversation\Messenger\AbstractMessenger;
use App\Entity\State;
use App\Message;
use App\User;

class General
{
    protected $triggers =
        [
          CreateCommand::class,
          InfoCommand::class,
          TimeCommand::class,
          ListCommand::class,
          StartCommand::class,
          DeleteCommand::class,
        ];

    public function run(User $user, Message $message, State $state, AbstractMessenger $messenger)
    {
        foreach ($this->triggers as $trigger) {

            /** @var AbstractCommand $command */
            $command = new $trigger($messenger);

            if ($command->hasCommand($state)) {
                $state = $command->commandAction($user, $message, $state);

                return $state;
            } elseif ($command->hasTrigger($message->text)) {
                $state = $command->triggerAction($user, $message);

                return $state;
            }

        }

        $getCommand = new GetCommand($messenger);
        $state = $getCommand->triggerAction($user, $message, $messenger);

        return $state;
    }

    /**
     * @return array
     */
    public function getTriggers() : array
    {
        return $this->triggers;
    }
}