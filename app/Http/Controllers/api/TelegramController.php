<?php

namespace App\Http\Controllers\api;

use App\Conversation\Answers\Stop;
use App\Conversation\Answers\Type;
use App\Conversation\CheckWay;
use App\Conversation\Conversation;
use App\Conversation\Keeper\Redis\Redis;
use App\Entity\State;
use App\Http\Controllers\Controller;
use App\Message;
use App\Parser\AllStops;
use App\Parser\FinalStops;
use App\Parser\Minsktrans;
use App\User;
use App\Way;
use Carbon\Carbon;
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


        /*Telegram::bot()->sendMessage(
            [
                'chat_id' => 221682466,
                'text' => $message->message
            ]
        );*/
        $redis = new Redis();
        $conversation = new Conversation($user, $message, $redis);

        $conversation->start();

    }

    public function test(Message $message, User $user)
    {
        $state = new State();
        $state->setNumber(17);
        $state->setType('Autobus');
        $state->setRoute('ДС Сухарево-5 - ДС Кунцевщина');
        $state->setStop('Лобанка');
        $r = CheckWay::getTime($state);
        dd([
            \DB::table('stops')->get(),
            $r
        ]);

    }

}
