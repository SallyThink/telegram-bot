<?php

namespace App\Conversation;

use Telegram;

class SendMessage
{
    private static $instance;
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
        if (!empty($message))
            $this->message[] = $message;
    }

    /**
     * @param int $id
     */
    public function sendMessage(int $id, $message = null)
    {
        foreach ($this->message as $message) {
            $message['chat_id'] = $id;
            $message['reply_to_message_id'] = $message;
            Telegram::bot()->sendMessage($message);
        }
    }
}