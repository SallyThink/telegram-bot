<?php

namespace App\Conversation\Keeper;

use App\Entity\State;

interface IKeeper
{
    public function getState($id);
    public function setState($id, $state);
    public function fill($id, State $state);
    public function save($id, State $state);


}