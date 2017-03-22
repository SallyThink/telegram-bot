<?php

namespace App\Conversation\Commands;

use App\Command;
use App\Conversation\Answers\Command\CommandFinishCreate;
use App\Conversation\Answers\Command\CommandName;
use App\Conversation\Answers\Command\CommandTime;
use App\Conversation\Answers\Command\ContinueCreate;
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

    public function start() : State
    {
        $this->addNewEmoji();
        $schedule = new Schedule();
        $schedule->setFlows($this->flows);

        $state = $schedule->action($this->message, $this->state);

        if ($state->getState() === ContinueCreate::class) {
            $this->addCommand($state);
            $this->addData($state);
        } elseif ($state->getState() === CommandFinishCreate::class) {
            $state->setCommand(null);
            $state->setState('');
        }

        return $state;
    }


    /**
     * @param State $state
     */
    protected function addCommand(State $state)
    {
        $model = new Command();

        if (null === $model->getCommand($this->user->chat_id, $state->getUserCommand())) {
            $model->chat_id = $this->user->chat_id;
            $model->command = $state->getUserCommand();
            $model->save();
         }
    }

    /**
     * @param State $state
     */
    protected function addData(State $state)
    {
        $model = (new Command())->getCommand($this->user->chat_id, $state->getUserCommand());
        $data = is_null($model->data) ? [] : $model->data;
        array_push($data, [
            'type' => $state->getType(),
            'number' => $state->getNumber(),
            'route' => $state->getRoute(),
            'stop' => $state->getStop(),
            'time' => $state->getTime()
        ]);
        $model->data = $data;
        $model->save();
    }

    /**
     * @return State
     */
    public function handle() : State
    {
        $this->addNewEmoji();
        SendMessage::getInstance()->addMessage((new CommandName(new State()))->answer());
        $this->state->setCommand('/create');
        $this->state->setState(CommandName::class);

        return $this->state;
    }

    protected function addNewEmoji()
    {
        $emoji = new Emoji();
        $emoji->createCommand();
    }

    /**
     * @param array $flows
     */
    public function setFlows(array $flows)
    {
        $this->flows = $flows;
    }

    /**
     * @return array
     */
    public function getFlows() : array
    {
        return $this->flows;
    }
}