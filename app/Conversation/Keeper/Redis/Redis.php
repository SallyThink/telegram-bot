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
    public function fill(int $id, State $state)
    {
        $state->setContext($this->redis->hGet($id, 'context'));
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
        $this->redis->hSet($id, 'context', $state->getContext());
        $this->redis->hSet($id, 'state', $state->getState());
        $this->redis->hSet($id, 'type', $this->translate($state->getType()));
        $this->redis->hSet($id, 'number', $state->getNumber());
        $this->redis->hSet($id, 'route', $state->getRoute());
        $this->redis->hSet($id, 'stop', $state->getStop());
        $this->redis->hSet($id, 'time', $state->getTime());

        $this->redis->expire($id, 180);
    }

    protected function translate(string $type)
    {
        switch($type) {
            case 'Автобус':
                return 'Autobus';
            case 'Троллейбус':
                return 'Trolleybus';
            case 'Трамвай':
                return 'Tramway';
        }
        return $type;
    }

    public function remove(int $id)
    {
        $this->redis->del($id);
    }

    public function setParam($id, $param, $val)
    {
        $this->redis->hSet($id, $param, $val);
    }

    public function expire($id)
    {
        $this->redis->expire($id, 180);
    }
}