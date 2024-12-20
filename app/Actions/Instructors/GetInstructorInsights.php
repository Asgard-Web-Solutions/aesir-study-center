<?php

namespace App\Actions\Instructors;

use App\Models\Insight;
use App\Models\Question;

class GetInstructorInsights
{
    public static function execute(Question $question)
    {
        $insights = Insight::where('question_id', $question->id)->where('ai_generated', '>', 0)->get();
        $insightsArray = array_fill(1, config('personalities.ai_count'), null);

        foreach ($insights as $insight) {
            $insightsArray[$insight->ai_generated] = $insight;
        }

        return $insightsArray;
    }
}
