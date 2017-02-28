<?php

namespace App\Conversation\Commands;

use App\Command;
use App\Conversation\Answers\Time;
use App\Conversation\SendMessage;
use App\Entity\State;
use App\Exceptions\ParserException;
use App\Message;
use App\User;
use Illuminate\Database\Eloquent\Model;

class GetCommand implements ICommand
{
    protected $state;
    protected $user;
    protected $message;

    public function __construct(User $user, Message $message, State $state)
    {
        $this->user = $user;
        $this->message = $message;
        $this->state = $state;
    }

    public function handle()
    {
        $command = (new Command())->getCommand($this->user->chat_id, $this->message->text);
        if(null === $command) {
            throw new ParserException();
        }
        $state = new State();
        foreach ($command->data as $v) {
            $state->setType($v['type']);
            $state->setNumber($v['number']);
            $state->setRoute($v['route']);
            $state->setStop($v['stop']);

            $time = new Time($state);
            $answer = $time->answer();
            $answer['text'] = '№' . $v['number'] . '=' . $answer['text'];
            SendMessage::getInstance()->addMessage($answer);
            
        }

        return $this->state;
    }
}