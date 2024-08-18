<?php

namespace App\Actions\ExamSession;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Set as ExamSet;

class CalculateQuestionTimeout 
{
    public static function execute($score, $correct = 1): Carbon {
        $hours = (config('test.hour_multiplier') * (min($score, 10) ** 2.6));

        if (!$correct) {
            $hours = $hours / 2;
        }
        
        return Carbon::now()->addHours($hours);
    }
}