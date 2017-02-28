<?php

namespace App\Http\Controllers\api;

use App\Command;
use App\Conversation\Answers\Route;
use App\Conversation\CheckWay;
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
        //dd(\DB::table('commands')->get());
        $a = ['/create','/pq','Автобус','17','ДС Сухарево-5 - ДС Кунцевщина', 'Лобанка'];
        $user->chat_id = 221682466;
        $message->text = '76';


        $redis = new Redis();
        //$state = $redis->fill($user->chat_id, new State());
        $conversation = new Conversation($user, $message, $redis);

        try {
            $conversation->start();
        } catch (ParserException $e) {
            $e->render($user->chat_id);
            dd('error exception');
        }

        //dd(\DB::table('commands')->get());

        $state = (new Redis())->fill(221682466);
        dd([
            'command' => $state->getCommand(),
            'state' => $state->getState(),
            'type' => $state->getType(),
            'number' => $state->getNumber(),
            'route' => $state->getRoute(),
            'stop' => $state->getStop(),
        ]);
    }

}
