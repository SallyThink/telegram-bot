<?php

namespace App\Conversation;

use App\Entity\State;
use App\Parser\Main;
use App\Route;
use App\Stop;
use App\Time;
use Carbon\Carbon;

class CheckWay
{

    /**
     * @param State $state
     * @return array
     */
    public static function getRoutes(State $state)
    {
        $vars = [
            'type' => $state->getType(),
            'number' => $state->getNumber(),
        ];

        $route = (new Route())->getRoute($vars);

        if (null === $route) {
            $parser = new Main($vars['type'], $vars['number']);
            $routes = $parser->getFinalStops();

            $vars['routes'] = $routes;
            $route = Route::create($vars);
        }

        return $route->getAttribute('routes');
    }

    /**
     * @param State $state
     * @return array
     */
    public static function getStops(State $state)
    {
        $vars = [
            'type' => $state->getType(),
            'number' => $state->getNumber(),
            'route' => $state->getRoute(),
        ];

        $stop = (new Stop())->getStop($vars);

        if (null === $stop) {
            $parser = new Main($vars['type'], $vars['number']);
            $stops = $parser->getAllStops($vars['route']);

            $vars['stops'] = $stops;
            $stop = Stop::create($vars);
        }

        return $stop->getAttribute('stops');
    }

    /**
     * @param State $state
     * @return array
     */
    public static function getTime(State $state)
    {
        $vars = [
            'type' => $state->getType(),
            'number' => $state->getNumber(),
            'route' => $state->getRoute(),
            'stop' => $state->getStop(),
            'isWeekend' => Carbon::now('Europe/Minsk')->isWeekend()
        ];

        $time = (new Time)->getTime($vars);

        if (null === $time) {
            $parser = new Main($vars['type'], $vars['number']);
            $time = $parser->getTime($vars['route'], $vars['stop']);

            $vars['time'] = $time;
            $time = Time::create($vars);
        }

        return $time->getAttribute('time');
    }

}