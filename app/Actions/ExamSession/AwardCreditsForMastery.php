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
    public static function execute(User $user, ExamSet $examSet, $originalMastery, $highestMastery): Void
    {
        $credits = $user->credit->first();

        // Award proficient mastery credits!
        if ($highestMastery == Mastery::Proficient->value && $originalMastery < Mastery::Proficient->value) {
            $creditRewards['architect'] = config('test.add_proficient_architect_credits');
            $creditRewards['study'] = config('test.add_proficient_study_credits');
            
            $credits->architect += $creditRewards['architect'];
            $credits->study += $creditRewards['study'];

            $credits->save();

            $title = 'Profciency Bonus';
            $desc = 'Bonus credits for achieving proficient in an exam set.';
            $history = RecordCreditHistory::execute($user, $title, $desc, $creditRewards);
            $history->set_id = $examSet->id;
            $history->save();
        }

        // Award Mastered mastery credits
        if ($highestMastery == Mastery::Mastered->value && $originalMastery < Mastery::Mastered->value) {
            $creditRewards['architect'] = config('test.add_mastered_architect_credits');
            $creditRewards['study'] = config('test.add_mastered_study_credits');

            $credits->architect += $creditRewards['architect'];
            $credits->study += $creditRewards['study'];

            $credits->save();

            $title = 'Mastery Bonus';
            $desc = 'Bonus credits for achieving Mastery in an exam set.';
            $history = RecordCreditHistory::execute($user, $title, $desc, $creditRewards);
            $history->set_id = $examSet->id;
            $history->save();
        }

        // Award credits to the exam author
        if (($examSet->user_id != $user->id) && ($highestMastery != $originalMastery)) {
            $creditRewards['architect'] = config('test.award_the_architect_architect_credits');
            $creditRewards['study'] = config('test.award_the_architect_study_credits');

            $levelMultiplier = 0;

            switch ($highestMastery) {
                case Mastery::Mastered->value:
                    $levelMultiplier = 0.5;
                    break;

                case Mastery::Proficient->value:
                    $levelMultiplier = 0.3;
                    break;
                
                case Mastery::Familiar->value:
                    $levelMultiplier = 0.2;
                    break;
            }

            if ($levelMultiplier > 0 && $examSet->user_id) {
                $architectCredits = User::find($examSet->user_id)->credit()->first();
                $architectCredits->architect += ($creditRewards['architect'] * $levelMultiplier);
                $architectCredits->study += ($creditRewards['study'] * $levelMultiplier);
    
                $architectCredits->save();
    
                $title = 'Author Mastery Bonus';
                $desc = 'Bonus credits for someone leveling up their mastery on an exam you authored!';
                $history = RecordCreditHistory::execute($examSet->user, $title, $desc, $creditRewards);
                $history->set_id = $examSet->id;
                $history->save();
            }
        }
    }
}
