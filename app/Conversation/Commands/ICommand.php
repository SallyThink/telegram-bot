<?php

namespace App\Conversation\Commands;

use App\Entity\State;
use App\Message;
use App\User;

interface ICommand
{
    /**
     * ICommand constructor.
     * @param User $user
     * @param Message $message
     * @param State $state
     */
    public function __construct(User $user, Message $message, State $state);

    /**
     * @return State
     */
    public function handle() : State;
}