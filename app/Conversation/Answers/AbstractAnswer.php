<?php

namespace App\Conversation\Answers;

use App\Entity\State;
use App\Message;
use Illuminate\Validation\Validator;
use Telegram\Bot\Keyboard\Keyboard;

abstract class AbstractAnswer
{
    protected $state;

    public function __construct(){}

    /**
     * @return array
     */
    abstract public function answer();

    /**
     * @param State $state
     * @param string|int $val
     * @return State
     */
    abstract public function setParam(State $state, $val);

    /**
     * @return array
     */
    abstract protected function getRules();

    /**
     * @param Message $message
     * @return bool|string
     */
    public function validation(Message $message)
    {
        $rules = $this->getRules();
        $validation = \Validator::make([key($rules) => $message->text] , $rules);
        $errors = $validation->errors();
        if ($errors->count() > 0) {
            return $errors->first();
        }
        return true;
    }

    /**
     * @param string $errorMessage
     * @return array
     */
    public function sendError(string $errorMessage)
    {
        $return = [
            'text' => $errorMessage,
            'reply_markup' => Keyboard::hide()
            ];

        return $return;
    }
}