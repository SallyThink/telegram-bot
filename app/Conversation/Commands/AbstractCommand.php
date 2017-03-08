<?php

namespace App\Conversation\Commands;

use App\Conversation\SendMessage;
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
    protected $triggers = [];
    protected $command;

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

    /**
     * @param string $trigger
     * @return bool
     */
    public function hasTrigger(string $trigger) : bool
    {
        return in_array($trigger, $this->triggers);
    }

    /**
     * @return array
     */
    public function getTriggers() : array
    {
        return $this->triggers;
    }

    /**
     * @param State $state
     * @return bool
     */
    public function hasCommand(State $state) : bool
    {
        return $state->getCommand() === $this->command;
    }

    /**
     * @return State
     */
    abstract public function handle() : State;

    /**
     * @return State
     */
    public function start() : State
    {
        return new State();
    }
}