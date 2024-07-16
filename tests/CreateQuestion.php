<?php

namespace Tests;

use App\Models\Set;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;

trait CreateQuestion
{
    public function CreateQuestion($attributes = []): Collection
    {
        $questions = Question::factory()->count(10)->create($attributes);

        return $questions;
    }
}
