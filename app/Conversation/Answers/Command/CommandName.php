<?php

namespace App\Conversation\Answers\Command;

use App\Command;
use App\Conversation\Answers\AbstractAnswer;
use App\Conversation\Commands\AbstractCommand;
use App\Conversation\Commands\General;
use App\Conversation\Messenger\TelegramMessenger;
use App\Entity\State;
use Illuminate\Database\Eloquent\Model;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Telegram\Bot\Keyboard\Keyboard;

class CommandName extends AbstractAnswer
{
    protected $state;

    public function __construct(State $state)
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
        $state->setUserCommand($val);

        return $state;
    }


    protected function getRules()
    {
        $rules = [];

        $commandNames = Command::where('chat_id', $this->state->getUserId())->get(['command']);
        foreach($commandNames as $name)
        {
            $rules[] = $name->command;
        }

        $triggers = (new General())->getTriggers();

        foreach ($triggers as $trigger) {
            // TODO :: NEED REFACTORING !!!
            /** @var AbstractCommand $obj */
            $obj = new $trigger(new TelegramMessenger());
            $rules[] = $obj->getCommand();
            $rules = array_merge($rules, $obj->getTriggers());
        }

        /*$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($engage));
        $rules = array_merge($rules, iterator_to_array($iterator, false));*/

        return ['command_name' => 'required|regex:/^\/.+$/|not_in:' . implode(',', $rules)];
    }
}