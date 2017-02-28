<?php

namespace App\Conversation\Commands;

use App\Entity\State;
use App\Message;
use App\User;

abstract class AbstractCommand
{
    /**
     * @var User
     */
    protected $user;
    /**
     * @var Message
     */
    protected $message;
    /**
     * @var State
     */
    protected $state;

    /**
     * AbstractCommand constructor.
     * @param User $user
     * @param Message $message
     * @param State $state
     */
    public function __construct(User $user, Message $message, State $state)
    {
        $this->user = $user;
        $this->message = $message;
        $this->state = $state;
    }
}