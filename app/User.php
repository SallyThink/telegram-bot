<?php

namespace App;


class User extends \Eloquent
{
    protected $fillable = [
        'chat_id', 'first_name', 'last_name', 'username',
    ];


    /**
     * @param \Telegram\Bot\Objects\User $user
     * @return mixed
     */
    public function store(\Telegram\Bot\Objects\User $user)
    {
        $values = [
            'chat_id' => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'username' => $user->getUsername(),
        ];

        return $this->firstOrCreate(['chat_id' => $values['chat_id']], $values);
    }
}
