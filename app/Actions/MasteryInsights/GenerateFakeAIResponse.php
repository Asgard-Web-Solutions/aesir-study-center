<?php

namespace App\Actions\MasteryInsights;

class GenerateFakeAIResponse
{
    public static function execute(): Array
    {
        return [
            'success' => true,
            'data' => 'This is a fake response that would be delivered from AI.',
        ];
    }
}
