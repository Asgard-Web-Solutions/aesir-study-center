<?php

namespace Tests;

use App\Models\Test;

trait TakeTest
{
    public function TakeTest($attributes = []): Test
    {
        $test = Test::factory()->user($attributes['user'])->exam($attributes['exam'])->create();

        return $test;
    }
}
