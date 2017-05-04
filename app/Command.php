<?php

namespace App;

class Command extends \Eloquent
{
    protected $fillable = ['chat_id', 'command', 'data'];
    protected $casts = ['data' => 'json'];

    public function __construct ()
    {
    }

    /**
     * @param $chatId
     * @param $commandName
     * @return array
     */
    public function getCommand($chatId, $commandName)
    {
        return $this->where('chat_id', $chatId)->where('command', $commandName)->get()->first();
    }

    /**
     * @param $chatId
     * @param $commandName
     */
    public function addCommand($chatId, $commandName)
    {
        if (null === $this->getCommand($chatId, $commandName)) {
            $this->create(['chat_id' => $chatId, 'command' => $commandName]);
        }
    }

    /**
     * @param $chatId
     * @param $commandName
     * @param $type
     * @param $number
     * @param $route
     * @param $stop
     * @param $time
     */
    public function addData($chatId, $commandName, $type, $number, $route, $stop, $time)
    {
        $command = $this->getCommand($chatId, $commandName);
        $data = is_null($command->data) ? [] : $command->data;
        array_push($data, [
            'type' => $type,
            'number' => $number,
            'route' => $route,
            'stop' => $stop,
            'time' => $time
        ]);
        $command->data = $data;
        $command->save();
    }
}
