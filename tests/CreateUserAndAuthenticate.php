<?php

namespace Tests;

use App\Models\User;

trait CreateUserAndAuthenticate
{
    public function CreateUserAndAuthenticate($attributes = []): User
    {
        $user = User::factory()->create($attributes);

        $this->actingAs($user);

        return $user;
    }
}
