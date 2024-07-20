<?php

namespace Tests;

use App\Models\Set;
use App\Models\Test;
use App\Models\User;
use App\Models\Question;
use DB;
use Carbon\Carbon;

trait StartExamSession
{
    public function StartExamSession(User $user, Set $exam)
    {
        $record = DB::table('exam_records')->where('set_id', $exam->id)->where('user_id', $user->id)->first();
        if (!$record) {
            DB::table('exam_records')->insert([
                'set_id' => $exam->id,
                'user_id' => $user->id,
            ]);
        }

        DB::table('exam_sessions')->insert([
            'user_id' => $user->id,
            'set_id' => $exam->id,
            'question_count' => 5,
            'questions_array' => '[7,4,2,5,1]',
            'current_question' => 0,
            'correct_answers' => 0,
            'incorrect_answers' => 0,
        ]);

        $session = DB::table('exam_sessions')->where('user_id', $user->id)->where('set_id', $exam->id)->where('date_completed', null)->first();

        $questions = Question::where('set_id', $exam->id)->get();
        foreach ($questions as $question) {
            DB::table('user_question')->insert([
                'user_id' => $user->id,
                'set_id' => $exam->id,
                'question_id' => $question->id,
                'score' => 2,
                'next_at' => Carbon::now(),
            ]);
        }

        return $session;
    }
}
