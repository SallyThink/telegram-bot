<?php

namespace App\Conversation\Messenger;

use App\Entity\State;
use App\Message;
use Telegram;
use Telegram\Bot\Keyboard\Keyboard;

class TelegramMessenger extends AbstractMessenger
{
   public function sendMessage(Message $msg = null, State $state)
   {
       $this->emojiCommand($state->getCommand());

       foreach ($this->message as $message) {
           $message['parse_mode'] = 'HTML';
           $message['text'] = implode(PHP_EOL, array_unique($this->emoji)) . $message['text'];
           $message['chat_id'] = $state->getUserId();
           $message['reply_to_message_id'] = $msg->message_id;
           isset($message['reply_markup']['keyboard']) ? $message['reply_markup']->row(Keyboard::button(['text' => "\u{1F519}"])) :
               Keyboard::make()->row(Keyboard::button(['text' => "\u{1F519}"]));
           Telegram::bot()->sendMessage($message);
       }
   }
}