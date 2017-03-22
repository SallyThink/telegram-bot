<?php

namespace App\Conversation;

interface IFlows
{
    /**
     * @param array $flows
     */
    public function setFlows(array $flows);

    /**
     * @return array
     */
    public function getFlows() : array;
}