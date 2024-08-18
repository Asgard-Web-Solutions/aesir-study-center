<?php

namespace Tests;

use App\Models\Question;
use App\Models\Set;
use App\Models\User;
use Carbon\Carbon;
use DB;

trait RegisterUserQuestions
{
    public function RegisterUserQuestions(User $user, Set $exam)
    {
        $now = Carbon::now()->subMinutes(2);

        $questions = Question::where('set_id', $exam->id)->get();
        foreach ($questions as $question) {
            DB::table('user_question')->insert([
                'user_id' => $user->id,
                'question_id' => $question->id,
                'set_id' => $exam->id,
                'score' => 0,
                'next_at' => $now
            ]);
        }
    }
}
