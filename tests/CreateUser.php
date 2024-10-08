<?php

namespace Tests;

use App\Models\User;

trait CreateUser
{
    public function CreateUser($attributes = null): User
    {
        $user = User::factory()->create($attributes);
        $this->GiveUserCredits($user);

        return $user;
    }
}
