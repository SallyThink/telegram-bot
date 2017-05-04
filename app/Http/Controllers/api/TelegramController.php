<?php

namespace App\Http\Controllers\api;

use App\Conversation\Conversation;
use App\Conversation\Keeper\Redis\Redis;
use App\Conversation\Messenger\TelegramMessenger;
use App\Exceptions\AnswerException;
use App\Exceptions\ParserException;
use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use Telegram;

class TelegramController extends Controller
{

    public function __construct ()
    {
    }

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

        $messenger = new TelegramMessenger();

        try {
            $conversation->start($messenger);
        } catch (ParserException $e) {
            $e->render($user->chat_id);
        } catch (AnswerException $e) {
            $e->render($user->chat_id);
        }// TODO:: catch error exception

    }

    public function test(Message $message, User $user)
    {
    }
}
