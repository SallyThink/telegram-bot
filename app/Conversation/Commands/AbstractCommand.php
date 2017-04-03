<?php

namespace App\Conversation\Commands;

use App\Conversation\Messenger\AbstractMessenger;
use App\Conversation\SendMessage;
use App\Entity\State;
use App\Message;
use App\User;

abstract class AbstractCommand
{
    protected $triggers = [];
    protected $command;
    protected $messenger;


    /**
     * AbstractCommand constructor.
     * @param AbstractMessenger $messenger
     */
    public function __construct(AbstractMessenger $messenger)
    {
        $this->messenger = $messenger;
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
     * @return string|null
     */
    public function getCommand()
    {
        return $this->command;
    }
    /**
     * @param User $user
     * @param Message $message
     * @return State
     */
    abstract public function triggerAction(User $user, Message $message) : State;

    /**
     * @param User $user
     * @param Message $message
     * @param State $state
     * @return State
     */
    public function commandAction(User $user, Message $message, State $state) : State
    {
        return $state;
    }

    /**
     * @param User $user
     * @return State
     */
    protected function getNewStateForTriggerAction(User $user) : State
    {
        $state = new State();
        $state->setCommand($this->command);
        $state->setState(isset($this->flows[0]) ? $this->flows[0] : null);
        $state->setUserId($user->chat_id);

        return $state;
    }
}