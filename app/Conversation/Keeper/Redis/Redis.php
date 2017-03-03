<?php

namespace App\Conversation\Keeper\Redis;

use App\Conversation\Keeper\IKeeper;
use App\Entity\State;

class Redis implements IKeeper
{
    protected $redis;

    public function __construct()
    {
        $this->redis = app()->make('redis');
    }

    /**
     * @param int $id
     * @param State $state
     * @return State
     */
    public function fill(int $id)
    {
        $state = new State();

        $state->setUserId($id);
        $state->setCommand($this->redis->hGet($id, 'command'));
        $state->setState($this->redis->hGet($id, 'state'));
        $state->setType($this->redis->hGet($id, 'type'));
        $state->setNumber($this->redis->hGet($id, 'number'));
        $state->setRoute($this->redis->hGet($id, 'route'));
        $state->setStop($this->redis->hGet($id, 'stop'));
        $state->setTime($this->redis->hGet($id, 'time'));

        return $state;
    }

    /**
     * @param int $id
     * @param State $state
     */
    public function save(int $id, State $state)
    {
        $this->redis->hSet($id, 'command', $state->getCommand());
        $this->redis->hSet($id, 'state', $state->getState());
        $this->redis->hSet($id, 'type', $state->getType());
        $this->redis->hSet($id, 'number', $state->getNumber());
        $this->redis->hSet($id, 'route', $state->getRoute());
        $this->redis->hSet($id, 'stop', $state->getStop());
        $this->redis->hSet($id, 'time', $state->getTime());

        $this->redis->expire($id, 180);
    }


    public function remove(int $id)
    {
        $this->redis->del($id);
    }

    protected function expire($id)
    {
        $this->redis->expire($id, 180);
    }
}