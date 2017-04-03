<?php

namespace App\Conversation\Messenger;

use App\Conversation\Helpers\Emoji;
use App\Entity\State;
use App\Message;

abstract class AbstractMessenger
{
    protected $emoji = [];
    protected $message = [];

    /**
     * @param array $message
     */
    public function addMessage(array $message)
    {
        if (!empty($message) && !empty($message['text']))
            $this->message[] = $message;
    }

    public function addEmoji(string $emoji)
    {
        $this->emoji[] = $emoji;
    }


    /**
     * @param Message|null $msg
     * @param State $state
     */
    abstract public function sendMessage(Message $msg = null, State $state);

    protected function emojiCommand($command)
    {
        $emoji = new Emoji();

        switch ($command) {
            case ('/create') :
                $this->addEmoji($emoji->createCommand());
                break;
            case ('/info') :
                $this->addEmoji($emoji->infoCommand());
                break;
            case ('/delete') :
                $this->addEmoji($emoji->deleteCommand());
                break;
        }
    }
}