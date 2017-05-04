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



    }

    public function test(Message $message, User $user)
    {
    }
}
