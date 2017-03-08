<?php

namespace App\Conversation\Commands;

use App\Conversation\SendMessage;
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
    {dd('test');
        if ($message->text == "\u{1F519}") {
            SendMessage::getInstance()->addMessage(['text' => 'back']);
        }
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
        $allTriggers = [];

        foreach ($this->triggers as $trigger) {
            $allTriggers = array_merge($allTriggers, (new $trigger(new User(), new Message(), new State()))->getTriggers());
        }

        return $allTriggers;
    }
}