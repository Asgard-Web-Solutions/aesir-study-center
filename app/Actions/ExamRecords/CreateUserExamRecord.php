<?php

namespace App\Actions\ExamRecords;

use DB;
use Carbon\Carbon;
use App\Models\User;
use Laravel\Pennant\Feature;
use App\Models\Set as ExamSet;

class CreateUserExamRecord 
{
    public static function execute(User $user, ExamSet $exam) {
        DB::table('exam_records')->insert([
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ]);

        if (Feature::active('mage-upgrade')) {
            if ($exam->user_id != auth()->user()->id) {
                $user->credit->study -= 1;
                $user->credit->save();
            }
        }

    }
}