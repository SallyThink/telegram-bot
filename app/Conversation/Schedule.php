<?php

namespace App\Conversation;

use App\Conversation\Answers\AbstractAnswer;
use App\Conversation\Answers\Number;
use App\Conversation\Answers\Stop;
use App\Conversation\Answers\Route;
use App\Conversation\Answers\Time;
use App\Conversation\Answers\Type;
use App\Conversation\Keeper\IKeeper;
use App\Entity\State;
use App\Message;
use App\User;
use Telegram;

class Schedule
{
    protected $user;
    protected $message;
    protected $state;

    protected $flows =
        [
            Type::class,
            Number::class,
            Route::class,
            Stop::class,
            Time::class
        ];

    public function __construct(User $user, Message $message, State $state)
    {
        $this->user = $user;
        $this->message = $message;
        $this->state = $state;
    }

    public function start()
    {
        $action = $this->action($this->state);

        if ($action->getState() === array_pop($this->flows)) {
            $this->state->setState($this->flows[0]);
            return $this->state;
        }

        return $this->state;
    }

    public function action(State $state)
    {
        $messenger = SendMessage::getInstance();

        $current = empty($state->getState()) ? $this->flows[0] : $state->getState();

        /** @var AbstractAnswer $currentState */
        $currentState = new $current($state);

        $validation = $currentState->validation($this->message);

        if (true !== $validation) {
            $messenger->addMessage($currentState->sendError($validation));
            $messenger->addMessage($currentState->answer());
            return $state;
        }
        $state = $currentState->setParam($state, $this->message->text);

        $next = $this->flows[array_search($state->getState(), $this->flows) + 1];

        $state->setState($next);


        /** @var AbstractAnswer $nextState */
        $nextState = new $next($state);
        $messenger->addMessage($nextState->answer());

        return $state;
    }

}