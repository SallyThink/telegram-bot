<?php

namespace App\Conversation;

use App\Message;
use Telegram;

class SendMessage
{
    private static $instance;
    public $emoji = [];
    private $message = [];

    private function __construct(){}
    private function __clone(){}


    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

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
     * @param int $id
     * @param Message|null $msg
     */
    public function sendMessage(int $id, Message $msg = null)
    {
        foreach ($this->message as $message) {
            $message['text'] = implode(PHP_EOL, $this->emoji) . $message['text'];
            $message['chat_id'] = $id;
            $message['reply_to_message_id'] = $msg->message_id;
            Telegram::bot()->sendMessage($message);
        }
    }
}