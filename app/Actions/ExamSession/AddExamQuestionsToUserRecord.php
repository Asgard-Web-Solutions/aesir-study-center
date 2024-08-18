<?php

namespace App\Actions\ExamSession;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Set as ExamSet;

class AddExamQuestionsToUserRecord 
{
    public static function execute(User $user, ExamSet $exam) {
        $now = new Carbon;
        $startTime = $now->clone()->subMinutes(2);

        foreach ($exam->questions as $question) {
            if (! $user->questions->contains($question)) {
                $user->questions()->attach($question->id, ['score' => 0, 'next_at' => $startTime, 'set_id' => $exam->id]);
            }
        }
    }
}