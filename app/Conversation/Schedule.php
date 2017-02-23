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

    protected $states =
        [
            Type::class,
            Number::class,
            Route::class,
            Stop::class,
            Time::class
        ];

    public function handle(User $user, Message $message, IKeeper $keeper)
    {
        $this->user = $user;
        $this->message = $message;
        $state = $keeper->fill($user->telegram_id, new State());
        $action = $this->action($state);
        if($action->getState() === array_pop($this->states)) {
            $keeper->remove($user->telegram_id);
            return true;
        }
        $keeper->save($user->telegram_id, $action);
    }

    public function action(State $state)
    {
        $userId = $this->user->telegram_id;
        $current = empty($state->getState()) ? $this->states[0] : $state->getState();

        /** @var AbstractAnswer $currentState */
        $currentState = new $current($state);

        $validation = $currentState->validation($this->message);

        if(true !== $validation) {
            $this->send($currentState->sendError($validation, $userId));
            $this->send($currentState->answer($userId));
            return false;
        }
        $next = $this->states[array_search($current, $this->states) + 1];

        $state->setState($next);
        $state = $currentState->setParam($state, $this->message->message);

        /** @var AbstractAnswer $nextState */
        $nextState = new $next($state);
        $this->send($nextState->answer($userId));

        return $state;
    }

    public function send($message)
    {
        $message['reply_to_message_id'] = $this->message->message_id;
        $a = Telegram::bot()->sendMessage($message);
    }
}