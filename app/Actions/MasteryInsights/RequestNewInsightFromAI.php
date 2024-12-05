<?php

namespace App\Actions\MasteryInsights;

use App\Models\Insight;
use App\Models\Question;
use OpenAI\Laravel\Facades\OpenAI;

class RequestNewInsightFromAI
{
    public static function execute(Question $question, $personality_id): Array
    {
        if (!$question) {
            return "ERROR, no Question supplied!";
        }

        $personality = config('personalities.ai.' . $personality_id);

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

        try {
            if (config('personalities.model') == 'none') {
                return [
                    'success' => true,
                    'data' => 'This is a fake response that would be delivered from AI.',
                ];
            }

            $result = OpenAI::chat()->create([
                'model' => config('personalities.model'),
                'messages' => [
                    // ['role' => 'system', 'content' => $personality['attitude'] . '\n' . config('personalities.job_instruction') . '\n' . config('personalities.coworkers') . '\n' . config('personalities.task')],
                    ['role' => 'system', 'content' => $personality['attitude'] . '\n' . config('personalities.job_instruction') . '\n' . config('personalities.task')],
                    ['role' => 'user', 'content' => 'I need help with this test question. Exam: ' . $question->set->name . '\nExam Description: ' . $question->set->description . '\nQuestion Text: ' . $question->text . '\n' . $listAnswers ],
                ],
            ]);

            return [
                'success' => true,
                'data' => $result->choices[0]->message->content, // Extract the content
            ];
        } catch (\OpenAI\Exceptions\ErrorException $e) {
            logger()->error('OpenAI API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'There was an issue with the OpenAI API. Please try again later.',
                'details' => $e->getMessage(), // Optional: include detailed error message
            ];
        } catch (\Exception $e) {
            logger()->error('General Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(), // Optional: include detailed error message
            ];
        }
    }
}
