<?php

namespace App\Conversation\Answers;

use App\Command;
use App\Conversation\Commands\General;
use App\Entity\State;
use Illuminate\Database\Eloquent\Model;
use Telegram\Bot\Keyboard\Keyboard;

class CommandName extends AbstractAnswer
{
    protected $state;

    public function __construct(State $state = null)
    {
        $this->state = $state;
    }

    public function answer()
    {
        $return = [
            'text' => 'command name',
            'reply_markup' => Keyboard::hide()
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
        return $state;
    }


    protected function getRules()
    {
        $commands = [];
        $commandNames = Command::where('chat_id', $this->state->getUserId())->get(['command']);
        foreach($commandNames as $name)
        {
            $commands[] = $name->command;
        }

        $rules = implode(',', $commands);

        $triggers = (new General())->getTriggers();

        foreach ($triggers as $trigger) {
            $rules .= $trigger;
        }


        return ['command_name' => 'required|regex:/^\/.+$/|not_in:' . substr($rules, 0, -1)];
    }
}