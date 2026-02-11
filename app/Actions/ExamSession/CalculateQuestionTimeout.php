<?php

namespace App\Actions\ExamSession;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Set as ExamSet;

class CalculateQuestionTimeout
{
    public static function execute($score, $correct = 1): Carbon
    {
        // Use max to ensure we get at least a small timeout even for score 0
        $effectiveScore = max($score, 1);
        $hours = (config('test.hour_multiplier') * (min($effectiveScore, 10) ** 2.6));

        if (!$correct) {
            $hours = $hours / 2;
        }
        
        return Carbon::now()->addHours($hours);
    }
}
