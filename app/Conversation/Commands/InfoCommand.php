<?php

namespace App\Conversation\Commands;

use App\Conversation\Answers\Command\CommandInfo;
use App\Conversation\Answers\Command\CommandList;
use App\Conversation\Answers\Command\CommandName;
use App\Conversation\Answers\Command\ContinueCreate;
use App\Conversation\Answers\Factory;
use App\Conversation\Answers\Type;
use App\Conversation\Helpers\Emoji;
use App\Conversation\IFlows;
use App\Conversation\Schedule;
use App\Conversation\SendMessage;
use App\Entity\State;
use App\Message;
use App\User;

class InfoCommand extends AbstractCommand implements ICommand, IFlows
{
    protected $triggers = ['/info'];
    protected $command = '/info';
    protected $flows = [
        CommandList::class,
        CommandInfo::class,
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

    /**
     * @param User $user
     * @param Message $message
     * @param State $state
     * @return State
     */
    public function commandAction(User $user, Message $message, State $state): State
    {
        $schedule = new Schedule();

        if ($state->getState() === $this->flows[1] && $message->text = 'Exit') {
            $state->setCommand(null);
            $state->setState(null);
            return $schedule->action($message, $state, $this->messenger);
        }

        $schedule->setFlows($this->flows);
        $state = $schedule->action($message, $state, $this->messenger);

        return $state;
    }

    /**
     * @return array
     */
    public function getFlows(): array
    {
        return $this->flows;
    }
}