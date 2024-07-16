<?php

namespace Tests;

use DB;
use App\Models\Set;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;

trait CreateQuestion
{
    public function CreateQuestion($attributes = []): Collection
    {
        $questions = Question::factory()->count(10)->create($attributes);
        foreach ($questions as $question) {
            DB::table('answers')->insert([
                'question_id' => $question->id,
                'text' => 'This is a test ' . $question->id,
                'correct' => 1,
            ]);
        }

        return $questions;
    }
}
