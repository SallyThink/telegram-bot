<?php

namespace App\Conversation\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    const TZ = 'Europe/Minsk';
    private $now;
    private $unixSinceDayStart;

    public function __construct()
    {
        $this->now = Carbon::now(self::TZ);
        $this->unixSinceDayStart = $this->now->timestamp % 86400;
    }

    /**
     * @param array $all
     * @return array
     */
    public function getNextTime(array $all) : array
    {
        sort($all);

        $time = [];

        for ($i = 0; $i < count($all); ++$i) {
            if ($this->unixSinceDayStart < $all[$i]) {
                $time[] = $all[$i];
            }
        }

        $next = array_slice($time, 0, 3);

        for ($i = 3; $i < count($time); ++$i) {
            if ($time[$i] - $time[$i-1] < 601 && count($next) < 6) {
                $next[] = $time[$i];
            } else {
                break;
            }
        }


        return $this->transformTimestamp($next);
    }

    /**
     * @param array $all
     * @return array
     */
    public function getLastTime(array $all) : array
    {
        sort($all);

        return $this->transformTimestamp(array_slice($all, -3));
    }

    /**
     * @param array $array
     * @return array
     */
    protected function transformTimestamp(array $array) : array
    {
        $return = [];
        foreach ($array as $value) {
            $timestamp = Carbon::createFromTimestamp($value, self::TZ);
            $return[] = substr($timestamp->toTimeString(), 0, -3);
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getTime() : string
    {
        $dateAndTime = $this->now->toDateString() . ' ' . $this->now->toTimeString();

        return $dateAndTime;
    }
}