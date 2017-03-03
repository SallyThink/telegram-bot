<?php

namespace App\Conversation\Helpers;

use App\Stop;

class StopHelper
{
    /**
     * @param array $data
     * @return array
     */
    public function checkOneStops(array $data) : array
    {
        $result = $data;
        $unset = [];

        for ($i = 0; $i < count($data); ++$i)
        {
            $result[$i]['type'] = [$data[$i]['type']];
            $result[$i]['number'] = [$data[$i]['number']];
            $result[$i]['route'] = [$data[$i]['route']];

            for ($q = $i+1; $q < count($data); ++$q)
            {
                if ($data[$i]['stop'] === $data[$q]['stop'] &&
                    $data[$i]['stop'] === $data[$q]['stop'] &&
                    $this->isOneRoute($data[$i], $data[$q]))
                {
                    $result[$i]['type'][] = $data[$q]['type'];
                    $result[$i]['number'][] = $data[$q]['number'];
                    $result[$i]['route'][] = $data[$q]['route'];
                    //$result[$i]['same'][$data[$q]['number']] = $data[$q]['route'];

                    $unset[] = $q;
                }
            }

        }

        foreach (array_unique($unset) as $v) {
            unset($result[$v]);
        }

        return $result;
    }

    /**
     * @param array $one
     * @param array $two
     * @return bool
     */
    protected function isOneRoute(array $one, array $two) : bool
    {

        $stops1 = Stop::where('type', $one['type'])->where('number', $one['number'])->where('route', $one['route'])->get()->first();
        $stops2 = Stop::where('type', $two['type'])->where('number', $two['number'])->where('route', $two['route'])->get()->first();

        $key1 = array_search($one['stop'], $stops1->stops);
        $key2 = array_search($two['stop'], $stops2->stops);

        if ($stops1->stops[$key1-1] == $stops2->stops[$key2-1] || /* && */ $stops1->stops[$key1+1] == $stops2->stops[$key2+1])
            return true;

        return false;
    }

}