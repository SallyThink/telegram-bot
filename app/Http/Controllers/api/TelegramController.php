<?php

namespace App\Http\Controllers\api;

use App\Command;
use App\Conversation\Answers\Route;
use App\Conversation\CheckWay;
use App\Conversation\Commands\General;
use App\Conversation\Commands\GetCommand;
use App\Conversation\Conversation;
use App\Conversation\Keeper\Redis\Redis;
use App\Conversation\SendMessage;
use App\Entity\State;
use App\Exceptions\ParserException;
use App\Http\Controllers\Controller;
use App\Message;
use App\Parser\M;
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
            $e->render($user->chat_id);
        }
    }

    public function test(Message $message, User $user)
    {
        
    }

}
