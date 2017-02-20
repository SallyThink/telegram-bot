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
     * @param $id
     * @param State $state
     * @return State
     */
    public function fill($id, State $state)
    {
        $state->setState((string)$this->redis->hGet($id, 'state'));
        $state->setType((string)$this->redis->hGet($id, 'type'));
        $state->setNumber((int)$this->redis->hGet($id, 'number'));
        $state->setRoute((string)$this->redis->hGet($id, 'route'));
        $state->setStop((string)$this->redis->hGet($id, 'stop'));
        $state->setTime((string)$this->redis->hGet($id, 'time'));

        return $state;
    }

    /**
     * @param $id
     * @param State $state
     */
    public function save($id, State $state)
    {
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
            case 'Тролейбус':
                return 'Trolleybus';
            case 'Трамвай':
                return 'Tramway';
        }
        return $type;
    }

    public function setState($id, $state)
    {
        $this->redis->hSet($id, 'state', $state);
    }

    public function remove($id)
    {
        $this->redis->del($id);
    }
    /**
     * @param integer $id
     * @return string
     */
    public function getState($id)
    {
        return (string)$this->redis->hGet($id, 'state');
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