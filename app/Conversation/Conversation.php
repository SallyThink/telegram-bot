<?php

namespace App\Conversation;

use App\Conversation\Answers\AbstractAnswer;
use App\Conversation\Answers\Number;
use App\Conversation\Answers\Route;
use App\Conversation\Answers\Stop;
use App\Conversation\Answers\Time;
use App\Conversation\Commands\General;
use App\Conversation\Keeper\IKeeper;
use App\Conversation\Answers\Type;
use App\Conversation\Messenger\AbstractMessenger;
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

    public function start(AbstractMessenger $messenger)
    {
        $state = $this->keeper->fill($this->user->chat_id);
        $text = $this->message->text;

        if ($text == "\u{1F519}") {

            $backAction = new BackAction($messenger);
            $state = $backAction->action($state);

        } elseif ('/' === substr($text, 0, 1) || null != $state->getCommand()) {

            $general = new General();
            $state = $general->run($this->user, $this->message, $state, $messenger);

        } else {

            $answer = new Schedule();
            $state = $answer->start($this->user, $this->message, $state, $messenger);

        }

        $this->keeper->save($this->user->chat_id, $state);

        $messenger->sendMessage($this->message, $state);
    }




}