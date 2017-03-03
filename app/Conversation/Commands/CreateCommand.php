<?php

namespace App\Conversation\Commands;

use App\Command;
use App\Conversation\Answers\CommandFinishCreate;
use App\Conversation\Answers\CommandName;
use App\Conversation\Answers\CommandTime;
use App\Conversation\Answers\ContinueCreate;
use App\Conversation\Answers\Number;
use App\Conversation\Answers\Stop;
use App\Conversation\Answers\Route;
use App\Conversation\Answers\Type;
use App\Conversation\Schedule;
use App\Conversation\SendMessage;
use App\Entity\State;
use App\Message;
use App\User;

class CreateCommand extends Schedule implements ICommand
{
    protected $triggers = [
        '/create',
        '/endcreate'
    ];

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

    public function __construct(User $user, Message $message, State $state)
    {
        SendMessage::getInstance()->addEmoji("\u{1F195}");

        parent::__construct($user, $message, $state);
    }

    public function creating()
    {
        $state = $this->state;

        $state = $this->action($state);

        if ($state->getCommand() === 'create') {
            $this->addCommand($state) ? $state->setCommand($this->message->text) : $state->setState(Type::class);
        }

        if ($state->getState() === ContinueCreate::class) {
            $this->addData($state);
        }

        if ($state->getState() === CommandFinishCreate::class) {
            $state->setCommand(null);
        }

        return $state;

    }

    protected function addCommand(State $state)
    {
        $model = new Command();
        if (null === $model->getCommand($this->user->chat_id, $this->message->text)) {
            $model->chat_id = $this->user->chat_id;
            $model->command = $this->message->text;
            $model->save();
            return true;
        }

        return false;
    }

    protected function addData(State $state)
    {
        $model = (new Command())->getCommand($this->user->chat_id, $state->getCommand());
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

    public function handle() : State
    {
        SendMessage::getInstance()->addMessage((new CommandName(new State()))->answer());
        $this->state->setCommand('create');
        $this->state->setState(CommandName::class);

        return $this->state;
    }
}