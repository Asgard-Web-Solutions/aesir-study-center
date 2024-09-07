<?php

namespace App\Actions\ExamRecords;

use App\Actions\User\RecordCreditHistory;
use DB;
use Carbon\Carbon;
use App\Models\User;
use Laravel\Pennant\Feature;
use App\Models\Set as ExamSet;

class CreateUserExamRecord
{
    public static function execute(User $user, ExamSet $exam)
    {
        DB::table('exam_records')->insert([
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ]);

        if (Feature::active('mage-upgrade')) {
            if ($exam->user_id != auth()->user()->id) {
                $user->credit->study -= 1;
                $user->credit->save();

                $title = 'Exam Enrollment';
                $desc = 'This exam was added to your account.';

                $credits['study'] = -1;
                $history = RecordCreditHistory::execute($user, $title, $desc, $credits);
                $history->set_id = $exam->id;
                $history->save();
            }
        }
    }
}
