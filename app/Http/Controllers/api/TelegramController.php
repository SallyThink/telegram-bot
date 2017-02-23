<?php

namespace App\Http\Controllers\api;

use App\Conversation\Answers\Route;
use App\Conversation\CheckWay;
use App\Conversation\Conversation;
use App\Conversation\Keeper\Redis\Redis;
use App\Entity\State;
use App\Exceptions\ParserException;
use App\Http\Controllers\Controller;
use App\Message;
use App\Parser\Minsktrans;
use App\User;
use Illuminate\Validation\Rule;
use League\Flysystem\Exception;
use Telegram;

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
            $e->render($user->telegram_id);
        }
    }

    public function test(Message $message, User $user)
    {
        $user->telegram_id = 221682466;
        $message->message = 'Автобус';
        $redis = new Redis();
        //$state = $redis->fill($user->telegram_id, new State());
        $conversation = new Conversation($user, $message, $redis);

        try {
            $conversation->start();
        } catch (ParserException $e) {
            $e->render($user->telegram_id);
        }
    }

}
