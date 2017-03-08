<?php

namespace App\Conversation\Commands;

use App\Command;
use App\Conversation\SendMessage;
use App\Entity\State;

class ListCommand extends AbstractCommand implements ICommand
{
    protected $triggers = [
        '/commands',
        '/mycommands',
        '/custom',
        '/customcommands',
        '/my',
    ];
    public function handle() : State
    {
        $messenger = SendMessage::getInstance();

        $commands = Command::where('chat_id', $this->user->chat_id)->get();

        if ($commands->isEmpty()) {
            $messenger->addMessage(['text' => 'You havent commands. Send /create for new command']);

            return $this->state;
        }

        $list = [];

        foreach ($commands as $command) {
            $list[] = $command->command;
        }

        SendMessage::getInstance()->addMessage(['text' => '<code>inline fixed-width code inline fixed-width code</code>' . PHP_EOL . '<pre>inline fixed-width code inline fixed-width code</pre>']);

        return $this->state;
    }
}