<?php

namespace App\Conversation;

use App\Conversation\Answers\AbstractAnswer;
use App\Conversation\Answers\Number;
use App\Conversation\Answers\Route;
use App\Conversation\Answers\Stop;
use App\Conversation\Answers\Time;
use App\Conversation\Commands\Command;
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
        $state = $this->keeper->fill($this->user->telegram_id, new State());
        if('/' === substr($this->message->message, 0, 1)) {
            $answer = new Command();
            return true;
        }
        $answer = new Schedule();

        $answer->handle($this->user, $this->message, $this->keeper);
        return true;
    }




}