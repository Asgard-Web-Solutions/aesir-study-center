<?php

namespace App\Actions\ExamSession;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Set as ExamSet;

class SelectQuestionsForExam 
{
    public static function execute(User $user, ExamSet $exam, $questionCount) {
        $now = Carbon::now();
        
        // Select number of questions requested
        $questions = DB::table('user_question')->where('user_id', $user->id)->where('set_id', $exam->id)->where('next_at', '<', $now)->get();

        // Shuffle and select the appropriate number of questions
        $questions = $questions->random($questionCount);
        $questions = $questions->shuffle();

        return $questions;
    }
}