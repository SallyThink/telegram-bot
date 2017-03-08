<?php

namespace App\Conversation\Answers\Command;

use Telegram\Bot\Keyboard\Keyboard;

class PossibleCommand
{
    protected $commands;

    public function __construct($commands)
    {
        $this->commands = $commands;
    }

    public function answer()
    {
        $return = [
            'text' => 'Possible command ' . PHP_EOL . implode(PHP_EOL, $this->commands),
            'reply_markup' => Keyboard::make([
                'keyboard' => [
                    ['Автобус'],
                    ['Троллейбус', 'Трамвай']
                ],
                'resize_keyboard' => false,
                'one_time_keyboard' => false,
            ])
        ];

        return $return;
    }
}