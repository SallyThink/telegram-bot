<?php

namespace App\Conversation;

use App\Message;
use Telegram;
use Telegram\Bot\Keyboard\Keyboard;

class SendMessage
{
    private static $instance;
    protected $emoji = [];
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
            $message['parse_mode'] = 'HTML';
            $message['text'] = implode(PHP_EOL, array_unique($this->emoji)) . $message['text'];
            $message['chat_id'] = $id;
            $message['reply_to_message_id'] = $msg->message_id;
            isset($message['reply_markup']['keyboard']) ? $message['reply_markup']->row(Keyboard::button(['text' => "\u{1F519}"])) : '' ;
            Telegram::bot()->sendMessage($message);
        }

    }
}