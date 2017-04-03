<?php

namespace App\Conversation\Answers\Command;

use App\Command;
use App\Conversation\Answers\AbstractAnswer;
use App\Conversation\Helpers\Emoji;
use App\Entity\State;
use Telegram\Bot\Keyboard\Keyboard;

class CommandInfo extends AbstractAnswer
{
    public function __construct(State $state)
    {
        $this->state = $state;
    }

    public function answer()
    {
        $emoji = new Emoji();
        $data = Command::where('command', $this->state->getUserCommand())->get()->first();

        $text = [];

        foreach ($data->data as $val) {
            $text[] = $emoji->typeEmoji($val['type']) . ' ' . $val['number'] . ' ' . $val['route'] . ' ' . $val['stop'] . ' ' . $val['time'];
        }

        return ['text' => implode(PHP_EOL, $text), 'reply_markup' => Keyboard::make()
            ->row(Keyboard::button(['text' => 'Exit']))->setResizeKeyboard(true)];
    }

    public function setParam(State $state, $val)
    {
        'Exit' === $val ? $state->setCommand(null) : null;

        return $state;
    }

    protected function getRules()
    {
        return ['continue' => 'in:Exit'];
    }
}