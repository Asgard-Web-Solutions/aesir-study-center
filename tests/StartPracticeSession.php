<?php

namespace Tests;

use App\Models\ExamPractice;
use App\Models\Question;
use App\Models\Set;
use App\Models\User;
use Carbon\Carbon;
use DB;

trait StartPracticeSession
{
    public function StartPracticeSession(User $user, Set $exam)
    {
        $record = ExamPractice::where('exam_id', $exam->id)->where('user_id', $user->id)->first();
        if (! $record) {
            ExamPractice::create([
                'exam_id' => $exam->id,
                'user_id' => $user->id,
                'question_count' => $exam->questions->count(),
                'question_order' => '[1,2,3,4,5,6,7,8,9,10]',
                'question_index' => 0,
            ]);
        }

        $session = ExamPractice::where('user_id', $user->id)->where('exam_id', $exam->id)->first();

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
