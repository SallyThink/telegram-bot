<?php

namespace App\Conversation\Commands;

use App\Conversation\SendMessage;
use App\Entity\State;
use App\Message;
use App\User;
use Carbon\Carbon;

class TimeCommand extends AbstractCommand implements ICommand
{
    public function handle()
    {
        $time = Carbon::now('Europe/Minsk');

        SendMessage::getInstance()->addMessage([
            'text' => $time->toDateString() .' '. $time->toTimeString()
        ]);

        return $this->state;
    }
}