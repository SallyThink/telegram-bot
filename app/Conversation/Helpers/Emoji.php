<?php

namespace App\Conversation\Helpers;

use App\Conversation\SendMessage;
use App\Entity\State;

class Emoji
{
    public function type(State $state)
    {
        $messenger = SendMessage::getInstance();
        switch ($state->getType()) {
            case ('Autobus'):
                $messenger->addEmoji("\u{1F68D}");
                break;
            case ('Trolleybus'):
                $messenger->addEmoji("\u{1F68E}");
                break;
            case ('Tramway'):
                $messenger->addEmoji("\u{1F68A}");
                break;
        }

    }

    public function createCommand()
    {
        SendMessage::getInstance()->addEmoji("\u{1F195}");
    }
}