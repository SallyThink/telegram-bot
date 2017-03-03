<?php

namespace App\Conversation\Commands;

use App\Command;
use App\Conversation\Answers\GetCommandTime;
use App\Conversation\Answers\PossibleCommand;
use App\Conversation\Answers\Time;
use App\Conversation\CheckWay;
use App\Conversation\Helpers\StopHelper;
use App\Conversation\Helpers\TimeHelper;
use App\Conversation\SendMessage;
use App\Entity\State;
use App\Exceptions\ParserException;
use App\Message;
use App\User;
use Illuminate\Database\Eloquent\Model;

class GetCommand extends AbstractCommand implements ICommand
{

    /**
     * @return State
     */
    public function handle() : State
    {
        $messenger = SendMessage::getInstance();
        $command = (new Command())->getCommand($this->user->chat_id, $this->message->text);

        if (null === $command) {
            $this->possibleCommand();
            return $this->state;
        }

        $helper = new StopHelper();
        $command = $helper->checkOneStops($command->data);

        $state = new State();

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

            $answer = new GetCommandTime($state, $numbers, $allTime);

            $messenger->addMessage($answer->answer());
        }

        return $this->state;
    }

    protected function possibleCommand()
    {
        $triggers = (new General())->getTriggers();
        $userCommands = Command::where('chat_id', $this->user->chat_id)->get(['command']);

        if ($userCommands->isNotEmpty()) {
            $userCommands = $userCommands->transform(function ($v) {
                return $v->command;
            })
                ->toArray();
        }

        $result = [];
        foreach (array_merge($triggers, $userCommands) as $v) {
            if (3 > levenshtein($this->message->text, $v))
                $result[] = $v;
        }

        $answer = new PossibleCommand();

        SendMessage::getInstance()->addMessage($answer->answer($result));

        return $this->state;
    }
}