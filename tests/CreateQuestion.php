<?php

namespace Tests;

use App\Models\Question;
use DB;

trait CreateQuestion
{
    public function CreateQuestion($attributes = []): Question
    {
        $question = Question::factory()->create($attributes);

        DB::table('answers')->insert([
            'question_id' => $question->id,
            'text' => 'Correct Answer '.$question->id,
            'correct' => 1,
        ]);

        if (! isset($attributes['group_id'])) {
            DB::table('answers')->insert([
                'question_id' => $question->id,
                'text' => 'Incorrect Answer '.$question->id,
                'correct' => 0,
            ]);
        }

        return $question;
    }
}
