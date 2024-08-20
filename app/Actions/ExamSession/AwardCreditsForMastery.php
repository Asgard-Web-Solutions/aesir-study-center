<?php

namespace App\Actions\ExamSession;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Enums\Mastery;
use App\Models\Set as ExamSet;

class AwardCreditsForMastery 
{
    public static function execute(User $user, ExamSet $examSet, $originalMastery, $highestMastery): Void {
        $credits = $user->credit->first();

        // Award proficient mastery!
        if ($highestMastery == Mastery::Proficient->value && $originalMastery < Mastery::Proficient->value) {
            $credits->architect += config('test.add_proficient_architect_credits');
            $credits->study += config('test.add_proficient_study_credits');

            $credits->save();
        }

        if ($highestMastery == Mastery::Mastered->value && $originalMastery < Mastery::Mastered->value) {
            $credits->architect += config('test.add_mastered_architect_credits');
            $credits->study += config('test.add_mastered_study_credits');

            $credits->save();
        }

        if (($examSet->user_id != $user->id) && ($highestMastery == Mastery::Mastered->value && $originalMastery < Mastery::Mastered->value)) {
            $architectCredits = User::find($examSet->user_id)->credit()->first();
            $architectCredits->architect += config('test.award_the_architect_architect_credits');
            $architectCredits->study += config('test.award_the_architect_study_credits');

            $architectCredits->save();
        }
    }
}