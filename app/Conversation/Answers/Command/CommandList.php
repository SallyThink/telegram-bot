<?php

namespace App\Conversation\Answers\Command;

use App\Command;
use App\Conversation\Answers\AbstractAnswer;
use App\Entity\State;
use Illuminate\Validation\Rule;

class CommandList extends AbstractAnswer
{
    protected $text;

    public function __construct(State $state)
    {
        $this->state = $state;
        $commands = Command::where('chat_id', $state->getUserId())->get();

        $this->text = $commands->isEmpty() ? ['You havent commands. Send /create for new command'] : $commands->transform(function ($v) {
            return $v->command;
        })->all();
    }

    public function answer()
    {
        $return = [
            'text' => PHP_EOL . implode(PHP_EOL, $this->text),
        ];

        return $return;
    }

    /**
     * @param State $state
     * @param $val
     * @return State
     */
    public function setParam(State $state, $val)
    {
        $state->setUserCommand($val);

        return $state;
    }


    protected function getRules()
    {
        $commands = Command::where('chat_id', $this->state->getUserId())->get()->transform(function ($command) {
           return $command->command;
        })->toArray();

        return ['command_name' => ['required', Rule::in($commands)]];
    }
}