<?php

namespace App\Actions\MasteryInsights;

use App\Models\Conversation;
use App\Models\Insight;
use App\Models\Question;
use OpenAI\Laravel\Facades\OpenAI;

class FormatAnswersForAI
{
    public static function execute(Question $question): String
    {
        $answers = $question->answers;
        $correct = '';
        $incorrect = '';

        foreach ($answers as $answer) {
            if ($answer->correct) {
                $correct .= "* " . $answer->text . '\n';
            } else {
                $incorrect .= "* " . $answer->text . '\n';
            }
        }

        $listAnswers = 'Correct Answers:\n' . $correct;

        if ($incorrect != '') {
            $listAnswers .= '\nIncorrect Answers:\n' . $incorrect;
        }

        return $listAnswers;
    }
}
