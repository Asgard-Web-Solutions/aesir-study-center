<?php

namespace App\Livewire;

use App\Actions\MasteryInsights\RecordInsightResponse;
use App\Actions\MasteryInsights\RequestNewInsightFromAI;
use App\Models\Insight;
use App\Models\Question;
use Flux\Flux;
use Livewire\Component;

class QuestionInsightsDisplay extends Component
{
    private ?Question $question = null;
    private $insights = null;
    private $personalities = null;

    public function mount(Question $question) {
        $this->question = $question;
    }

    public function summon(Question $question, $id) {
        $this->question = $question;
        $response = RequestNewInsightFromAI::execute($question, $id);

        if ($response['success']) {
            $insight = RecordInsightResponse::execute($question, $id, $response['data']);
            $this->getInsights();
            Flux::toast('Instructor summoned! Reload the page if you do not see the Mastery Insight!');
        } else {
            Flux::toast(variant: 'danger', text: 'There was an error communicating with the Instructor. Please try again later.');
        }
    }

    public function getInsights() {
        $insights = array();
        $personalities = array();

        for ($i = 0; $i <= config('personalities.ai_count'); $i ++) {
            if ($i == 0) {
                $personalities[$i] = [
                    'id' => 0,
                    'name' => $this->question->set->user->name,
                    'title' => 'Author',
                    'species' => 'human',
                    'avatarUrl' => $this->question->set->user->gravatarUrl(),
                ];
            } else {
                $personalities[$i] = config('personalities.ai.' . $i);
                $personalities[$i]['avatarUrl'] = asset('images/' . $personalities[$i]['avatar']);
            }

            $insights[$i] = $this->question->insights->where('ai_generated', $i)->first() ?? null;
        }

        $this->personalities = $personalities;
        $this->insights = $insights;
    }

    public function render()
    {
        $personalities = array();
        $this->getInsights();

        return view('livewire.question-insights-display', [
            'insights' => $this->insights,
            'personalities' => $this->personalities,
            'question' => $this->question,
        ]);
    }
}
