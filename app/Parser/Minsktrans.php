<?php

namespace App\Parser;

use Carbon\Carbon;

class Minsktrans
{
    protected $url = 'http://www.minsktrans.by/pda/index.php?&submit=+OK+';

    protected $day;

    protected $body;
    protected $routesBody;

    protected $number;
    protected $type;

    protected $patternForAnotherWay = "/(.*)Другие направления/u";
    protected $patternForFinalStop = "/<br><br><b>([а-яА-Я0-9 -№]*)<\/b>/u";
    protected $patternForAllStop = '/<a href=\'([a-zA-Z0-9=?&%№]*)\'>([а-яА-Я0-9 -\.]+)<\/a>/u';

    protected $allStops = [];
    protected $finalStops = [];
    protected $time = [];

    /**
     * @param string $type
     * @param int $number
     */
    public function __construct(string $type, int $number)
    {
        $this->number = $number;
        $this->type = $type;
        $this->day = Carbon::now('Europe/Minsk')->dayOfWeek;
        $this->init();
        $this->checkForAnotherWays();
    }

    protected function init()
    {
        $this->body = file_get_contents($this->url . '&n=' . $this->number . '&Transport=' . $this->type . '&day=' . $this->day);
    }

    /**
     *  remove another ways
     */
    protected function checkForAnotherWays()
    {
         if(preg_match($this->patternForAnotherWay, $this->body, $body)) {
             $this->body = $body[1];
         }
    }

    /**
     *  parse final stops
     */
    protected function parseFinalStops()
    {
        preg_match_all($this->patternForFinalStop, $this->body, $result);

        $this->finalStops = $result[1];
    }

    /**
     *  parse routes, A to B and B to A
     */
    protected function parseRoutes()
    {
        $patternForRoute = '/' . $this->finalStops[0] . '(.*)' . $this->finalStops[1] . '(.*)/u';

        preg_match_all($patternForRoute, $this->body, $result);

        $this->routesBody[0] = $result[1][0];
        $this->routesBody[1] = $result[2][0];
    }

    /**
     * @return array
     */
    public function getFinalStops()
    {
        $this->parseFinalStops();

        return $this->finalStops;
    }

    /**
     * @param string $route (A to B/B to A)
     */
    protected function parseAllStops(string $route)
    {
        $this->parseFinalStops();
        $this->parseRoutes();

        preg_match_all($this->patternForAllStop, $this->routesBody[array_search($route, $this->finalStops)], $this->allStops);
    }

    /**
     * @param string $route
     * @return array
     */
    public function getAllStops(string $route)
    {
        $this->parseAllStops($route);

        return $this->allStops[2];
    }

    protected function parseTime($route, $stop)
    {
        $this->parseFinalStops();
        $this->parseRoutes();
        $this->parseAllStops($route);

        $body = file_get_contents('http://www.minsktrans.by/pda/index.php' . $this->allStops[1][array_search($stop, $this->allStops[2])]);

        preg_match_all('/<b>([0-9]*)<\/b> :?  ([0-9 ]*)/', str_replace(['<u>', '</u>'], '', $body), $m);

        for($i=0; $i<count($m[0]); ++$i)
        {
            $hour = $m[1][$i];
            if(0 == substr($hour, 0, 1)) {
                $hour = substr($hour, 1, 1);
            }
            $this->time[$hour] = $m[2][$i];
        }
    }

    public function getTime($route, $stop)
    {
        $this->parseTime($route, $stop);

        return $this->time;
    }
}