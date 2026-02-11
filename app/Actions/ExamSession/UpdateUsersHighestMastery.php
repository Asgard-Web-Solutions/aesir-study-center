<?php

namespace App\Actions\ExamSession;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Enums\Mastery;
use App\Models\Set as ExamSet;

class UpdateUsersHighestMastery
{
    public static function execute(User $user, ExamSet $examSet, Int $originalMastery): Int
    {
        $masteryLevelMastered = 0;
        $masteryLevelProficient = 0;
        $masteryLevelFamiliar = 0;
        $masteryLevelApprentice = 0;

        $questions = DB::table('user_question')->where('user_id', $user->id)->where('set_id', $examSet->id)->get();

        foreach ($questions as $question) {
            if ($question->score >= config('test.grade_mastered')) {
                $masteryLevelMastered++;
                $masteryLevelProficient++;
                $masteryLevelFamiliar++;
                $masteryLevelApprentice++;
            } elseif ($question->score >= config('test.grade_proficient')) {
                $masteryLevelProficient++;
                $masteryLevelFamiliar++;
                $masteryLevelApprentice++;
            } elseif ($question->score >= config('test.grade_familiar')) {
                $masteryLevelFamiliar++;
                $masteryLevelApprentice++;
            } elseif ($question->score >= config('test.grade_apprentice')) {
                $masteryLevelApprentice++;
            }
        }

        $highestMastery = Mastery::Unskilled->value;

        if ($masteryLevelMastered == $questions->count()) {
            $highestMastery = Mastery::Mastered->value;
        } elseif ($masteryLevelProficient == $questions->count()) {
            $highestMastery = Mastery::Proficient->value;
        } elseif ($masteryLevelFamiliar == $questions->count()) {
            $highestMastery = Mastery::Familiar->value;
        } elseif ($masteryLevelApprentice == $questions->count()) {
            $highestMastery = Mastery::Apprentice->value;
        }

        $highestMastery = max($highestMastery, $originalMastery);

        DB::table('exam_records')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->update([
            'mastery_apprentice_count' => $masteryLevelApprentice,
            'mastery_familiar_count' => $masteryLevelFamiliar,
            'mastery_proficient_count' => $masteryLevelProficient,
            'mastery_mastered_count' => $masteryLevelMastered,
            'highest_mastery' => $highestMastery,
        ]);

        return $highestMastery;
    }
}
