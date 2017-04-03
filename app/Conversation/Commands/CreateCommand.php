<?php

namespace App\Conversation\Commands;

use App\Command;
use App\Conversation\Answers\Command\CommandFinishCreate;
use App\Conversation\Answers\Command\CommandName;
use App\Conversation\Answers\Command\CommandTime;
use App\Conversation\Answers\Command\ContinueCreate;
use App\Conversation\Answers\Factory;
use App\Conversation\Answers\Number;
use App\Conversation\Answers\Stop;
use App\Conversation\Answers\Route;
use App\Conversation\Answers\Type;
use App\Conversation\Helpers\Emoji;
use App\Conversation\IFlows;
use App\Conversation\Schedule;
use App\Conversation\SendMessage;
use App\Entity\State;
use App\Message;
use App\User;

class CreateCommand extends AbstractCommand implements ICommand, IFlows
{
    protected $triggers = [
        '/create',
        '/new',
    ];

    protected $command = '/create';

    protected $flows = [
        CommandName::class,
        Type::class,
        Number::class,
        Route::class,
        Stop::class,
        CommandTime::class,
        ContinueCreate::class,
        CommandFinishCreate::class,
    ];

    public function commandAction(User $user, Message $message, State $state) : State
    {
        $schedule = new Schedule();
        $schedule->setFlows($this->flows);

        $state = $schedule->action($message, $state, $this->messenger);

        if ($state->getState() === ContinueCreate::class) {
            $model = new Command();
            $model->addCommand($user->chat_id, $state->getUserCommand());
            $model->addData($user->chat_id, $state->getUserCommand(), $state->getType(),
                $state->getNumber(), $state->getRoute(), $state->getStop(), $state->getTime());
        } elseif ($state->getState() === CommandFinishCreate::class) {
            $state->setCommand(null);
            $state->setState('');
        }

        return $state;
    }

    /**
     * @param User $user
     * @param Message $message
     * @return State
     */
    public function triggerAction(User $user, Message $message) : State
    {
        $state = $this->getNewStateForTriggerAction($user);

        $this->messenger->addMessage(Factory::create($this->flows[0], $state)->answer());

        return $state;
    }

    /**
     * @return array
     */
    public function getFlows() : array
    {
        return $this->flows;
    }
}