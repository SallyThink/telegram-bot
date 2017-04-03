<?php

namespace App\Conversation;

use App\Conversation\Answers\AbstractAnswer;
use App\Conversation\Answers\Factory;
use App\Conversation\Answers\Number;
use App\Conversation\Answers\Stop;
use App\Conversation\Answers\Route;
use App\Conversation\Answers\Time;
use App\Conversation\Answers\Type;
use App\Conversation\Helpers\Emoji;
use App\Conversation\Keeper\IKeeper;
use App\Conversation\Messenger\AbstractMessenger;
use App\Entity\State;
use App\Message;
use App\User;

class Schedule implements IFlows
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

    public function start(User $user, Message $message, State $state, AbstractMessenger $messenger)
    {
        $state = $this->action($message, $state, $messenger);

        if ($state->getState() === array_pop($this->flows)) {
            $state->setState($this->flows[0]);
            return $state;
        }

        return $state;
    }

    /**
     * @param Message $message
     * @param State $state
     * @param AbstractMessenger $messenger
     * @return State
     */
    public function action(Message $message, State $state, AbstractMessenger $messenger) : State
    {
        $current = empty($state->getState()) ? $this->flows[0] : $state->getState();

        $currentState = Factory::create($current, $state);

        $validation = $currentState->validation($message);

        if (true !== $validation) {
            $messenger->addMessage($currentState->sendError($validation));
            $messenger->addMessage($currentState->answer());
            return $state;
        }

        $state = $currentState->setParam($state, $message->text);

        $nextFlowKey = $state->getState() === $this->flows[count($this->flows) - 1] ? 0 : array_search($state->getState(), $this->flows) + 1;
        $next = $this->flows[$nextFlowKey];

        $state->setState($next);

        $emoji = new Emoji();
        $emoji->type($state, $messenger);

        $nextState = Factory::create($next, $state);
        $messenger->addMessage($nextState->answer());

        return $state;
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