<?php

namespace App\Conversation;

use App\Conversation\Answers\AbstractAnswer;
use App\Conversation\Answers\Number;
use App\Conversation\Answers\Route;
use App\Conversation\Answers\Stop;
use App\Conversation\Answers\Time;
use App\Conversation\Commands\Command;
use App\Conversation\Commands\General;
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


    public function __construct(User $user, Message $message, IKeeper $keeper)
    {
        $this->user = $user;
        $this->message = $message;
        $this->keeper = $keeper;
    }

    public function start()
    {
        $state = $this->keeper->fill($this->user->chat_id);

        if ('/' === substr($this->message->text, 0, 1) || null != $state->getCommand()) {

            $general = new General();
            $state = $general->run($this->user, $this->message, $state);

        } else {

            $answer = new Schedule();
            $state = $answer->start($this->user, $this->message, $state);

        }

        $this->keeper->save($this->user->chat_id, $state);
        
        SendMessage::getInstance()->sendMessage($this->user->chat_id, $this->message);
    }




}