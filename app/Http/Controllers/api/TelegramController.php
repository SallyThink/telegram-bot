<?php

namespace App\Http\Controllers\api;

use App\Conversation\Conversation;
use App\Conversation\Keeper\Redis\Redis;
use App\Entity\State;
use App\Exceptions\ParserException;
use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use Telegram;
use Telegram\Bot\Keyboard\Keyboard;

class TelegramController extends Controller
{

    public function start(User $user, Message $message)
    {
        $update = Telegram::bot()->getWebhookUpdate();

        $telegramMessage = $update->getMessage();
        $telegramUser = $telegramMessage->getFrom();

        /**
         * @var User $user
         */
        $user = $user->store($telegramUser);

        /**
         * @var Message $message
         */
        $message = $message->store($telegramMessage);

        $redis = new Redis();

        $conversation = new Conversation($user, $message, $redis);

        try {
            $conversation->start();
        } catch (ParserException $e) {
            $e->render($user->chat_id);
        } // TODO:: catch error exception

    }

    public function test(Message $message, User $user)
    {

    }

}
