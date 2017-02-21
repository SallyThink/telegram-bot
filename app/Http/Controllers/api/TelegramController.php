<?php

namespace App\Http\Controllers\api;

use App\Conversation\CheckWay;
use App\Conversation\Conversation;
use App\Conversation\Keeper\Redis\Redis;
use App\Entity\State;
use App\Exceptions\ParserException;
use App\Http\Controllers\Controller;
use App\Message;
use App\Parser\Minsktrans;
use App\User;
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
       $state = new State();
       $state->setNumber(17);
       $state->setType('Autobus');
       $state->setStop('Лобанка');
       $state->setRoute('ДС Сухарево-5 - ДС Кунцевщина');
        dd([
            CheckWay::getRoutes($state),
            CheckWay::getStops($state),
            CheckWay::getTime($state)
        ]);
    }

}
