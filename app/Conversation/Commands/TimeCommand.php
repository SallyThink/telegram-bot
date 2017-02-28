<?php

namespace App\Conversation\Commands;

use App\Conversation\SendMessage;
use App\Entity\State;
use App\Message;
use App\User;
use Carbon\Carbon;

class TimeCommand implements ICommand
{
    protected $user;
    protected $message;
    protected $state;

    public function __construct(User $user, Message $message, State $state)
    {
        $this->user = $user;
        $this->message = $message;
        $this->state = $state;
    }

    public function handle()
    {
        $time = Carbon::now('Europe/Minsk');

        SendMessage::getInstance()->addMessage([
            'text' => $time->toDateString() .' '. $time->toTimeString()
        ]);

        return $this->state;
    }
}