<?php

namespace App\Conversation\Answers;

use Telegram\Bot\Keyboard\Keyboard;

class PossibleCommand
{
    public function answer(array $commands)
    {
        $return = [
            'text' => 'Possible command ' . PHP_EOL . implode(PHP_EOL, $commands),
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