<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\Insight;
use Livewire\Component;

class QuestionInsightsConversation extends Component
{
    public $personality;
    public ?Conversation $conversation;
    public ?Insight $insight;

    public function mount($personality, Insight $insight) {
        $this->personality = $personality;
        $this->insight = $insight;
        $this->conversation = Conversation::where('insight_id', $insight->id)->first();
    }

    public function createConversation(Insight $insight) {
        $conversation = Conversation::create([
            'insight_id' => $insight->id,
            'user_id' => auth()->id(),
            'title' => 'New Conversation'
        ]);

        $this->conversation = $conversation;
    }

    public function render()
    {
        return view('livewire.question-insights-conversation');
    }
}
