<?php

namespace App\Parser;

use App\Exceptions\ParserException;
use Carbon\Carbon;

class Minsktrans
{
    protected $url = 'http://www.minsktrans.by/pda/index.php';

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
        $url = $this->url . '?submit=+OK+' . '&n=' . $this->number . '&Transport=' . $this->type . '&day=' . $this->day;
        $this->body = $this->curl($url);
    }
    protected function curl($url)
    {
        $headers =[
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language:ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
        ];
/*        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3013.3 Safari/537.36");
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_REFERER, $this->url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, "");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "");
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = curl_exec($ch);
        curl_close($ch);*/
$result = file_get_contents($url);

        if(false === $result || false === $this->isValid($result)) {
            throw new ParserException();
        }

        return $result;
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

    protected function isValid($body)
    {
        $pattern = '/(В этот день маршрут не работает)|(Направления не найдены)/u';
        preg_match($pattern, $body, $m);

        return empty($m);
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

        $url = $this->url . $this->allStops[1][array_search($stop, $this->allStops[2])];
        $body = $this->curl($url);

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