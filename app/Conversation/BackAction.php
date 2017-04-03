<?php

namespace App\Conversation;

use App\Conversation\Answers\AbstractAnswer;
use App\Conversation\Answers\Factory;
use App\Conversation\Answers\Type;
use
    App\Conversation\Commands\AbstractCommand;
use App\Conversation\Commands\General;
use App\Conversation\Messenger\AbstractMessenger;
use App\Entity\State;
use App\Message;
use App\User;

class BackAction
{
    protected $messenger;
    private $defaultState = Type::class;

    public function __construct(AbstractMessenger $messenger)
    {
        $this->messenger = $messenger;
    }

    /**
     * @param State $state
     * @return State
     */
    public function action(State $state) : State
    {
        if ('' != $state->getCommand()) {
            $general = new General();
            $allCommands = $general->getTriggers();

            foreach ($allCommands as $command)
            {
                /** @var AbstractCommand $class */
                $class = new $command($this->messenger);
                if ($class->hasCommand($state) && $class instanceof IFlows) {
                    $flows = $class->getFlows();
                }
            }

        } else {
            $schedule = new Schedule();
            $flows = $schedule->getFlows();
        }

        if (0 == array_search($state->getState(), $flows)) {
            $state->setCommand(null);
            $state->setState(null);
            $this->answer($state);

            return $state;
        }

        $prevState = $this->previousState($flows, $state);
        $this->answer($state);

        return $prevState;
    }

    /**
     * @param array $flows
     * @param State $state
     * @return State
     */
    protected function previousState(array $flows, State $state) : State
    {
        $currentStateKey = array_search($state->getState(), $flows);

        $prevState = $flows[$currentStateKey - 1];
        $state->setState($prevState);

        return $state;
    }

    /**
     * @param State $state
     */
    public function answer(State $state)
    {
        $answer = empty($state->getState()) ? $this->defaultState : $state->getState();
        /** @var AbstractAnswer $answer */
        $answer = Factory::create($answer, $state);

        $this->messenger->addMessage($answer->answer());
    }
}