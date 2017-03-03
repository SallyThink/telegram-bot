<?php

namespace App\Conversation\Commands;

use App\Command;
use App\Conversation\SendMessage;
use App\Entity\State;

class ListCommand extends AbstractCommand implements ICommand
{
    public function handle() : State
    {
        $messenger = SendMessage::getInstance();

        $commands = Command::where('chat_id', $this->user->chat_id)->get();

        if ($commands->isEmpty()) {
            $messenger->addMessage(['text' => 'You havent commands. Send /create for new command']);

            return $this->state;
        }

        $list = '';

        foreach ($commands as $command) {
            $list = $command->command . "\n";
        }

        SendMessage::getInstance()->addMessage(['parse_mode' => 'HTML', 'text' => $list]);

        return $this->state;
    }
}