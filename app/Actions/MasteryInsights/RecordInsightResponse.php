<?php

namespace App\Actions\MasteryInsights;

use App\Models\Insight;
use App\Models\Question;

class RecordInsightResponse
{
    public static function execute(Question $question, $personality, $text)
    {
        $insight = $question->where('ai_personality', $personality)->first();
        if (!$insight) {
            $insight = new Insight();

            $insight->ai_generated = $personality;
            $insight->question_id = $question->id;
            $insight->user_id = auth()->id();
        }

        $insight->insight_text = $text;

        $insight->save();

        return $insight;
    }
}
