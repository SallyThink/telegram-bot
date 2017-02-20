<?php

namespace App\Conversation;

use App\Conversation\Answers\AbstractAnswer;
use App\Conversation\Answers\Number;
use App\Conversation\Answers\Route;
use App\Conversation\Answers\Stop;
use App\Conversation\Answers\Time;
use App\Conversation\Keeper\IKeeper;
use App\Conversation\Answers\Type;
use App\Entity\State;
use App\Message;
use App\User;
use Telegram;

class Conversation
{
    protected $user;
    protected $message;

    /**
     * @var IKeeper $keeper
     */
    protected $keeper;
    protected $states =
        [
            Type::class,
            Number::class,
            Route::class,
            Stop::class,
            Time::class
        ];

    public function __construct(User $user, Message $message, IKeeper $keeper)
    {
        $this->user = $user;
        $this->message = $message;
        $this->keeper = $keeper;
    }

    public function start()
    {
        $userId = $this->user->telegram_id;
        /** @var State $entity */
        $entity = $this->keeper->fill($userId, new State());
        $state = $this->keeper->getState($userId);

        if(null == $state) {
            $state = Type::class;
            $currentState = new Type($entity);
            $this->keeper->setState($userId, Type::class);
        } else {
            /** @var AbstractAnswer $currentState */
            $currentState = new $state($entity);
        }

        $validation = $currentState->validation($this->message);

        if($validation->errors()->count() > 0) {
            $this->send($currentState->sendError($validation, $userId));
            $this->send($currentState->answer($userId));
        } else {
            $next = $this->states[array_search($state, $this->states)+1];

            $entity->setState($next);
            $entity = $currentState->setParam($entity, $this->message->message);

            if($next != $this->states[4]) {
                $this->keeper->save($userId, $entity);
            } else {
                $this->keeper->remove($userId);
            }

            $nextState = new $next($entity);
            $this->send($nextState->answer($userId));
        }

    }

    public function send($message)
    {
        $message['reply_to_message_id'] = $this->message->message_id;
        Telegram::bot()->sendMessage($message);
    }

}