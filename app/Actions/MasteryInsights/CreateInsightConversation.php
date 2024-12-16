<?php

namespace App\Actions\MasteryInsights;

use App\Models\Conversation;
use App\Models\Insight;

class CreateInsightConversation
{
    public static function execute(Insight $insight): Conversation
    {
        $conversation = Conversation::create([
            'insight_id' => $insight->id,
            'user_id' => auth()->id(),
            'title' => 'New Conversation'
        ]);

        return $conversation;
    }
}
