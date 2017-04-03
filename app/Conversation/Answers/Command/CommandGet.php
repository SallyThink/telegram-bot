<?php

namespace App\Conversation\Answers\Command;

use App\Conversation\Answers\Time;
use App\Conversation\Helpers\TimeHelper;
use App\Entity\State;

class CommandGet extends Time
{
    public function __construct(State $state, $numbers, $allTime)
    {
        $helper = new TimeHelper();
        $time = $state->getTime() === 'Last' ? $helper->getLastTime($allTime) : $helper->getNextTime($allTime);

        $this->time = implode(',', $numbers) . PHP_EOL . implode(' ', $time == null ? $this->emptyTimeSentence : $time);
    }
}