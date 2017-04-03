<?php

namespace App\Conversation\Commands;

use App\Command;
use App\Conversation\Answers\Command\CommandGet;
use App\Conversation\Answers\Command\PossibleCommand;
use App\Conversation\CheckWay;
use App\Conversation\Helpers\StopHelper;
use App\Conversation\Messenger\AbstractMessenger;
use App\Conversation\SendMessage;
use App\Entity\State;
use App\Message;
use App\User;

class GetCommand extends AbstractCommand implements ICommand
{


    /**
     * @param User $user
     * @param Message $message
     * @return State
     */
    public function triggerAction(User $user, Message $message) : State
    {
        $state = $this->getNewStateForTriggerAction($user);

        $command = (new Command())->getCommand($user->chat_id, $message->text);

        if (null === $command) {
            $this->possibleCommand($user, $message);
            return $state;
        }

        $helper = new StopHelper();

        $command = $helper->checkOneStops($command->data);

        foreach ($command as $v) {
            $state->setStop($v['stop']);
            $state->setTime($v['time']);

            $allTime = [];
            $numbers = [];

            for ($i = 0; $i < count($v['number']); ++$i) {
                $state->setType($v['type'][$i]);
                $state->setNumber($v['number'][$i]);
                $state->setRoute($v['route'][$i]);

                foreach (CheckWay::getTime($state) as $time) {
                    $allTime[] = $time;
                }
                $numbers[] = $v['number'][$i] ;
            }

            $answer = new CommandGet($state, $numbers, $allTime);

            $this->messenger->addMessage($answer->answer());
        }

        return $state;
    }

    protected function possibleCommand(User $user, Message $message)
    {
        $triggers = (new General())->getTriggers();
        $userCommands = Command::where('chat_id', $user->chat_id)->get(['command']);

        if ($userCommands->isNotEmpty()) {
            $userCommands = $userCommands->transform(function ($v) {
                return $v->command;
            })
                ->toArray();
        }

        $result = [];
        foreach (array_merge($triggers, $userCommands) as $v) {
            if (3 > levenshtein($message->text, $v))
                $result[] = $v;
        }

        $answer = new PossibleCommand($result);

        $this->messenger->addMessage($answer->answer());
    }
}