<?php

namespace App\Livewire;

use App\Models\Insight;
use App\Models\Question;
use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\Validate;

class QuestionInsightsEditor extends Component
{
    public ?Insight $insight = null;
    public Question $question;

    #[Validate('string|required')]
    public string $insight_text = '';

    public function mount(Question $question) {
        $this->question = $question;
        $this->insight = $question->insights->where('ai_generated', '=', 0)->first();
        $this->insight_text = $this->insight?->insight_text ?? '';
    }

    public function save() {
        $this->validate();

        if ($this->insight == null) {
            $this->insight = new Insight();
            $this->insight->ai_generated = 0;
        }

        $this->insight->insight_text = $this->insight_text;
        $this->insight->question_id = $this->question->id;
        $this->insight->user_id = auth()->id();

        $this->insight->save();

        Flux::toast('Mastery Insight Saved');
        $this->modal('edit-insight')->close();
    }

    public function render()
    {
        return view('livewire.question-insights-editor');
    }
}
