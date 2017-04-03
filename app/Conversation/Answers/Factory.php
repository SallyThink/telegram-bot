<?php

namespace App\Conversation\Answers;

use App\Entity\State;
use App\Exceptions\AnswerException;

class Factory
{
    /**
     * @param string $answer
     * @param State|null $state
     * @return mixed
     * @throws AnswerException
     */
    public static function create(string $answer, State $state = null) : AbstractAnswer
    {
        if (class_exists($answer)) {
            return new $answer($state);
        }

        throw new AnswerException();
    }
}