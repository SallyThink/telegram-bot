<?php

namespace App\Conversation\Keeper;

use App\Entity\State;

interface IKeeper
{
    /**
     * @param int $id
     * @param State $state
     * @return State
     */
    public function fill(int $id, State $state);
    /**
     * @param int $id
     * @param State $state
     */
    public function save(int $id, State $state);
    public function remove(int $id);

}