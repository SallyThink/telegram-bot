<?php

namespace App\Conversation;

use App\Conversation\Answers\AbstractAnswer;
use App\Conversation\Answers\Number;
use App\Conversation\Answers\Stop;
use App\Conversation\Answers\Route;
use App\Conversation\Answers\Time;
use App\Conversation\Answers\Type;
use App\Conversation\Helpers\Emoji;
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

    public function start(User $user, Message $message, State $state)
    {
        $state = $this->action($this->flows, $message, $state);

        if ($state->getState() === array_pop($this->flows)) {
            $state->setState($this->flows[0]);
            return $state;
        }

        return $state;
    }

    public function action(array $flows, Message $message, State $state)
    {
        $messenger = SendMessage::getInstance();

        $current = empty($state->getState()) ? $flows[0] : $state->getState();

        /** @var AbstractAnswer $currentState */
        $currentState = new $current($state);

        $validation = $currentState->validation($message);

        if (true !== $validation) {
            $messenger->addMessage($currentState->sendError($validation));
            $messenger->addMessage($currentState->answer());
            return $state;
        }
        $state = $currentState->setParam($state, $message->text);

        $next = $flows[array_search($state->getState(), $flows) + 1];

        $state->setState($next);

        $emoji = new Emoji();
        $emoji->type($state);

        /** @var AbstractAnswer $nextState */
        $nextState = new $next($state);
        $messenger->addMessage($nextState->answer());

        return $state;
    }

}