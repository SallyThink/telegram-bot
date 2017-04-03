<?php

namespace App\Conversation\Commands;

use App\Command;
use App\Conversation\Answers\Command\CommandDelete;
use App\Conversation\Answers\Command\CommandEnd;
use App\Conversation\Answers\Command\CommandList;
use App\Conversation\Answers\Factory;
use App\Conversation\IFlows;
use App\Conversation\Schedule;
use App\Conversation\SendMessage;
use App\Entity\State;
use App\Message;
use App\User;

class DeleteCommand extends AbstractCommand implements ICommand, IFlows
{
    protected $triggers = ['/delete'];
    protected $command = '/delete';
    protected $flows = [
        CommandList::class,
        CommandDelete::class,
        CommandEnd::class,
    ];

    /**
     * @param User $user
     * @param Message $message
     * @return State
     */
    public function triggerAction(User $user, Message $message): State
    {
        $state = $this->getNewStateForTriggerAction($user);

        $answer = Factory::create($this->flows[0], $state);

        $this->messenger->addMessage($answer->answer());

        return $state;
    }

    public function commandAction(User $user, Message $message, State $state): State
    {
        $schedule = new Schedule();
        $schedule->setFlows($this->flows);
        $state = $schedule->action($message, $state, $this->messenger);

        //$this->flows[2] === $state->getState() ? $state->setState($this->flows[0]) : null;

        return $state;
    }

    public function getFlows(): array
    {
        return $this->flows;
    }
}