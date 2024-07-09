<?php

namespace Tests;

use App\Models\Set;
use App\Models\Question;

trait CreateQuestion
{
    public function CreateQuestion($attributes = []): Question
    {
        $question = Question::factory()->create($attributes);

        return $question;
    }
}
