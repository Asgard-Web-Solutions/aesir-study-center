<?php

namespace App\Actions\ExamSession;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Set as ExamSet;

class SelectQuestionsForExam
{
    public static function execute(User $user, ExamSet $exam, $questionCount, ?int $lessonId = null)
    {
        $now = Carbon::now();
        
        // Select number of questions requested
        $query = DB::table('user_question')
            ->where('user_question.user_id', $user->id)
            ->where('user_question.set_id', $exam->id)
            ->where('user_question.next_at', '<', $now);

        // Filter by lesson if provided
        if ($lessonId) {
            $query->join('questions', 'user_question.question_id', '=', 'questions.id')
                  ->where('questions.lesson_id', $lessonId)
                  ->select('user_question.*');
        }

        $questions = $query->get();

        // Shuffle and select the appropriate number of questions
        $questions = $questions->random($questionCount);
        $questions = $questions->shuffle();

        return $questions;
    }
}
