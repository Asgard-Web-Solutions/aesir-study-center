<?php

namespace App\Actions\ExamSession;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Set as ExamSet;

class CalculateUsersMaxAvailableQuestions
{
    public static function execute(User $user, ExamSet $exam): int
    {
        $now = Carbon::now();
        $maxQuestions = DB::table('user_question')
            ->where('user_id', $user->id)
            ->where('set_id', $exam->id)
            ->where('next_at', '<', $now)
            ->count();

        if ($maxQuestions == 0) {
            $totalQuestions = DB::table('user_question')
                ->where('user_id', $user->id)
                ->where('set_id', $exam->id)
                ->count();

            if ($totalQuestions == 0) {
                // There are no questions at all, so let's set this to the max available
                $maxQuestions = $exam->questions->count();
            }
        }

        return $maxQuestions;
    }
}
