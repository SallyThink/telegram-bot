<?php

namespace App\Entity;

class State
{
    /**
     * @var int
     */
    protected $userId;
    /**
     * @var string
     */
    protected $command;
    /**
     * @var string
     */
    protected $state;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var int
     */
    protected $number;
    /**
     * @var string
     */
    protected $route;
    /**
     * @var string
     */
    protected $stop;
    /**
     * @var string
     */
    protected $time;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId)
    {
        $this->userId = (int)$userId;
    }
    /**
     * @param string $command
     * @return State
     */
    public function setCommand($command)
    {
        $this->command = $command;
        return $this;
    }
    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }
    /**
     * @param string $state
     * @return State
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }
    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
    /**
     * @param string $type
     * @return State
     */
    public function setType($type)
    {
        $this->type = (string)$type;
        return $this;
    }
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @param int $number
     * @return State
     */
    public function setNumber($number)
    {
        $this->number = (int)$number;
        return $this;
    }
    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }
    /**
     * @param string $route
     * @return State
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }
    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }
    /**
     * @param string $stop
     * @return State
     */
    public function setStop($stop)
    {
        $this->stop = $stop;
        return $this;
    }
    /**
     * @return string
     */
    public function getStop()
    {
        return $this->stop;
    }
    /**
     * @param string $time
     * @return State
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }
    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }
}
