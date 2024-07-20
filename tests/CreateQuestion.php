<?php

namespace Tests;

use DB;
use App\Models\Set;
use App\Models\Question;
use Attribute;
use Illuminate\Database\Eloquent\Collection;

trait CreateQuestion
{
    public function CreateQuestion($attributes = []): Question
    {
        $question = Question::factory()->create($attributes);

        DB::table('answers')->insert([
            'question_id' => $question->id,
            'text' => 'Correct Answer ' . $question->id,
            'correct' => 1,
        ]);

        if (!isset($attributes['group_id'])) {
            DB::table('answers')->insert([
                'question_id' => $question->id,
                'text' => 'Incorrect Answer ' . $question->id,
                'correct' => 0,
            ]);
        }

        return $question;
    }
}
