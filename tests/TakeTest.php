<?php

namespace Tests;

use App\Models\Set;
use App\Models\Test;
use App\Models\User;

trait TakeTest
{
    public function TakeTest($attributes = []): Test
    {
        $test = Test::factory()->user($attributes['user'])->exam($attributes['exam'])->create();

        return $test;
    }
}
