<?php

namespace App\Actions\ExamSession;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Set as ExamSet;

class CalculateUsersMaxAvailableQuestions
{
    public static function execute(User $user, ExamSet $exam, ?int $lessonId = null): int
    {
        $now = Carbon::now();
        
        $query = DB::table('user_question')
            ->where('user_question.user_id', $user->id)
            ->where('user_question.set_id', $exam->id)
            ->where('user_question.next_at', '<', $now);

        // Filter by lesson if provided
        if ($lessonId) {
            $query->join('questions', 'user_question.question_id', '=', 'questions.id')
                  ->where('questions.lesson_id', $lessonId);
        }

        $maxQuestions = $query->count();

        if ($maxQuestions == 0) {
            $totalQuery = DB::table('user_question')
                ->where('user_question.user_id', $user->id)
                ->where('user_question.set_id', $exam->id);

            // Filter by lesson if provided
            if ($lessonId) {
                $totalQuery->join('questions', 'user_question.question_id', '=', 'questions.id')
                           ->where('questions.lesson_id', $lessonId);
            }

            $totalQuestions = $totalQuery->count();

            if ($totalQuestions == 0) {
                // There are no questions at all, so let's set this to the max available
                if ($lessonId) {
                    $maxQuestions = $exam->questions()->where('lesson_id', $lessonId)->count();
                } else {
                    $maxQuestions = $exam->questions->count();
                }
            }
        }

        return $maxQuestions;
    }
}
