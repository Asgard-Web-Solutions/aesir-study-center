<?php

namespace App\Livewire;

use App\Actions\MasteryInsights\CreateInsightConversation;
use App\Actions\MasteryInsights\HaveInsightDialogWithAI;
use App\Actions\MasteryInsights\SendInsightDialogToAI;
use App\Actions\MasteryInsights\StartInsightDialogWithAI;
use App\Models\Conversation;
use App\Models\Dialog;
use App\Models\Insight;
use Livewire\Component;
use Livewire\Attributes\On;
use Flux\Flux;

#[On('refresh-the-component')]
class QuestionInsightsConversation extends Component
{
    public $personality;
    public ?Conversation $conversation;
    public ?Insight $insight;
    public $textMessage = '';

    public function mount($personality, Insight $insight) {
        $this->personality = $personality;
        $this->insight = $insight;
        $this->conversation = Conversation::where('insight_id', $insight->id)->first();
    }

    public function createConversation(Insight $insight) {
        $this->conversation = CreateInsightConversation::execute($insight);

        $response = StartInsightDialogWithAI::execute($this->conversation);

        if ($response['success']) {
            $dialog = Dialog::create([
                'conversation_id' => $this->conversation->id,
                'personality_id' => $insight->ai_generated,
                'message' => $response['data'],
            ]);
            $this->dispatch('refresh-the-component');
            Flux::toast('Conversation Started!');
        } else {
            Flux::toast(variant: 'danger', text: 'There was an error communicating with the Instructor. Please try again later.');
        }
    }

    public function SendMessage(Conversation $conversation) {
        $text = $this->textMessage;
        $this->textMessage = '';

        $dialog = Dialog::create([
            'conversation_id' => $this->conversation->id,
            'personality_id' => 0,
            'user_id' => auth()->id(),
            'message' => $text,
        ]);

        $this->dispatch('refresh-the-component');
        $response = HaveInsightDialogWithAI::execute($conversation);

        if ($response['success']) {
            $dialog = Dialog::create([
                'conversation_id' => $this->conversation->id,
                'personality_id' => $conversation->insight->ai_generated,
                'message' => $response['data'],
            ]);
            $this->dispatch('refresh-the-component');
            Flux::toast('Conversation Started!');
        } else {
            Flux::toast(variant: 'danger', text: 'There was an error communicating with the Instructor. Please try again later.');
        }

    }

    public function render()
    {
        return view('livewire.question-insights-conversation');
    }
}
