<?php

namespace App\Actions\ExamSession;

use App\Actions\User\RecordCreditHistory;
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
            $creditRewards['architect'] = config('test.add_proficient_architect_credits');
            $creditRewards['study'] = config('test.add_proficient_study_credits');
            
            $credits->architect += $creditRewards['architect'];
            $credits->study += $creditRewards['study'];

            $credits->save();

            $history = RecordCreditHistory::execute($user, 'Profciency Bonus', 'Bonus credits for achieving proficient in an exam set.', $creditRewards);
            $history->set_id = $examSet->id;
            $history->save();
        }

        if ($highestMastery == Mastery::Mastered->value && $originalMastery < Mastery::Mastered->value) {
            $creditRewards['architect'] = config('test.add_mastered_architect_credits');
            $creditRewards['study'] = config('test.add_mastered_study_credits');

            $credits->architect += $creditRewards['architect'];
            $credits->study += $creditRewards['study'];

            $credits->save();

            $history = RecordCreditHistory::execute($user, 'Mastery Bonus', 'Bonus credits for achieving Mastery in an exam set.', $creditRewards);
            $history->set_id = $examSet->id;
            $history->save();
        }

        if (($examSet->user_id != $user->id) && ($highestMastery == Mastery::Mastered->value && $originalMastery < Mastery::Mastered->value)) {
            $creditRewards['architect'] = config('test.award_the_architect_architect_credits');
            $creditRewards['study'] = config('test.award_the_architect_study_credits');

            $architectCredits = User::find($examSet->user_id)->credit()->first();
            $architectCredits->architect += $creditRewards['architect'];
            $architectCredits->study += $creditRewards['study'];

            $architectCredits->save();

            $history = RecordCreditHistory::execute($examSet->user, 'Author Mastery Bonus', 'Bonus credits for someone achieving Mastery for an exam you authored!', $creditRewards);
            $history->set_id = $examSet->id;
            $history->save();
        }
    }
}