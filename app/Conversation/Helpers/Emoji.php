<?php

namespace App\Conversation\Helpers;

use App\Conversation\Messenger\AbstractMessenger;
use App\Entity\State;

class Emoji
{
    public function type(State $state, AbstractMessenger $messenger)
    {
        $messenger->addEmoji($this->typeEmoji($state->getType()));
    }

    public function typeEmoji($type)
    {
        $types = ['Autobus' => "\u{1F68D}", 'Trolleybus' => "\u{1F68E}", 'Tramway' => "\u{1F68A}"];

        return isset($types[$type]) ? $types[$type] : '';
    }

    public function createCommand()
    {
        return "\u{1F195}";
    }

    public function infoCommand()
    {
        return "\u{2139}";
    }

    public function deleteCommand()
    {
        return "\u{1F525}";
    }
}