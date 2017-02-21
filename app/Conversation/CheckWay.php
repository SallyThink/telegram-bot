<?php

namespace App\Conversation;

use App\Entity\State;
use App\Parser\Minsktrans;
use App\Route;
use App\Stop;
use App\Time;

class CheckWay
{
    public static function getRoutes(State $state)
    {
        $vars = [
            'type' => $state->getType(),
            'number' => $state->getNumber(),
        ];

        $route = (new Route())->checkRoute($vars);

        if(null === $route) {
            $minsktrans = new Minsktrans($vars['type'], $vars['number']);
            $routes = $minsktrans->getFinalStops();

            $vars['routes'] = $routes;
            $route = Route::create($vars);
        }

        return $route->getAttribute('routes');
    }

    public static function getStops(State $state)
    {
        $vars = [
            'type' => $state->getType(),
            'number' => $state->getNumber(),
            'route' => $state->getRoute(),
        ];

        $stop = (new Stop())->getStop($vars);

        if(null === $stop) {
            $minsktrans = new Minsktrans($vars['type'], $vars['number']);
            $stops = $minsktrans->getAllStops($vars['route']);

            $vars['stops'] = $stops;
            $stop = Stop::create($vars);
        }

        return $stop->getAttribute('stops');
    }

    public static function getTime(State $state)
    {
        $vars = [
            'type' => $state->getType(),
            'number' => $state->getNumber(),
            'route' => $state->getRoute(),
            'stop' => $state->getStop()
        ];

        $time = (new Time())->getTime($vars);

        if(null === $time) {
            $minsktrans = new Minsktrans($vars['type'], $vars['number']);
            $time = $minsktrans->getTime($vars['route'], $vars['stop']);

            $vars['time'] = $time;
            $time = Time::create($vars);
        }

        return $time->getAttribute('time');
    }
}