<?php

namespace App\Conversation\Commands;

interface ICommand
{
    public function handle(string $message);
}