<?php

namespace App\Entity;

class State
{
    /**
     * @var string
     */
    protected $context;
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
     * @param string $context
     * @return State
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }
    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
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
        $this->type = $type;
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
