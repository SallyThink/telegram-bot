<?php

namespace App\Conversation\Commands;

class Command
{
    protected $triggers = [
        Create::class => '/create'
    ];

    public function start(string $message)
    {
        foreach($this->triggers as $key => $value) {

            if($message === $value) {
                /** @var ICommand $command */
                $command = app($key);
                $command->handle($message);
                break;
            }

        }
    }
}