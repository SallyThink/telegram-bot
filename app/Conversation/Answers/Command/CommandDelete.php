<?php

namespace App\Conversation\Answers\Command;

use App\Command;
use App\Conversation\Answers\AbstractAnswer;
use App\Entity\State;
use Telegram\Bot\Keyboard\Keyboard;

class CommandDelete extends AbstractAnswer
{
    protected $state;

    public function __construct(State $state)
    {
        $this->state = $state;
    }

    /**
     * @return array
     */
    public function answer()
    {
        return ['text' => 'Are you sure?', 'reply_markup' => Keyboard::make()
            ->row(Keyboard::button(['text' => 'Yes']))->setOneTimeKeyboard(true)];
    }

    /**
     * @param State $state
     * @param string|int $val
     * @return State
     */
    public function setParam(State $state, $val)
    {
        'Yes' === $val ? Command::where('command', $state->getUserCommand())->where('chat_id', $state->getUserId())->delete() : null;

        return $state;
    }

    /**
     * @return array
     */
    protected function getRules()
    {
        return ['areYouSure' => 'in:Yes,No'];
    }
}