<?php

namespace App\Conversation\Commands;

use App\Entity\State;
use App\Message;
use App\User;

interface ICommand
{
    /**
     * @param User $user
     * @param Message $message
     * @return State
     */
    public function triggerAction(User $user, Message $message) : State;
}