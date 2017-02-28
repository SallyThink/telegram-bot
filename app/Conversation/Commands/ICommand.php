<?php

namespace App\Conversation\Commands;

use App\Entity\State;
use App\Message;
use App\User;

interface ICommand
{
    public function __construct(User $user, Message $message, State $state);

    public function handle();
}