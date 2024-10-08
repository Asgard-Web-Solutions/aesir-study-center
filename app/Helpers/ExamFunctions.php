<?php

namespace App\Helpers;

use App\Models\Set as ExamSet;
use App\Models\User;
use Carbon\Carbon;

class ExamFunctions
{
    public static function initiate_questions_for_authed_user(ExamSet $exam)
    {
        $user = User::find(auth()->user()->id);
        $now = new Carbon;
        $start = $now->clone()->subMinutes(2);

        foreach ($exam->questions as $question) {
            if (! $user->questions->contains($question)) {
                $user->questions()->attach($question->id, ['score' => 0, 'next_at' => $start, 'set_id' => $exam->id]);
            }
        }
    }
}
