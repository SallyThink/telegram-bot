<?php

namespace App\Exceptions;

use App\Entity\State;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Telegram;

class AnswerException extends \Exception
{
    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        //log
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  int $id
     */
    public function render($id)
    {
        Telegram::bot()->sendMessage(
            [
                'chat_id' => $id,
                'text' => 'Answer exception',
            ]
        );
    }
}
