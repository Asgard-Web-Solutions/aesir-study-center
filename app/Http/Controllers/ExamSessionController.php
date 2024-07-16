<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Set;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ExamSessionConfigurationRequest;

class ExamSessionController extends Controller
{
    public function start(Set $examSet) {
        $this->authorize('view', $examSet);

        // Track that the user has taken this exam if they haven't before
        $examSet->records()->syncWithoutDetaching(auth()->user()->id);

        // Get current test sessions
        $session = $examSet->sessions()->wherePivot('date_completed', null)->get();

        if (!$session->count()) {
            return redirect()->route('exam-session.configure', $examSet);
        }
    }

    public function configure(Set $examSet) {
        $this->authorize('view', $examSet);

        return view('exam_session.configure')->with([
            'examSet' => $examSet,
        ]);
    }

    public function store(ExamSessionConfigurationRequest $request, Set $examSet) {
        $this->authorize('view', $examSet);

        // Initiate questions for the user
        $user = User::find(auth()->user()->id);
        $now = new Carbon();
        $start = $now->clone()->subMinutes(2);

        foreach ($examSet->questions as $question) {
            if (! $user->questions->contains($question)) {
                $user->questions()->attach($question->id, ['score' => 0, 'next_at' => $start, 'set_id' => $examSet->id]);
            }
        }

        // Select number of questions requested
        $questions = DB::table('user_question')->where('user_id', $user->id)->where('set_id', $examSet->id)->where('next_at', '<', $now)->get();

        // Shuffle and select the appropriate number of questions
        $questions = $questions->random($request->question_count);
        $questionArray = array();
        $questionArray = $questions->pluck('question_id');

        // Create a new instance of this test
        $examSet->sessions()->attach(auth()->user()->id, ['question_count' => $request->question_count, 'questions_array' => json_encode($questionArray), 'current_question' => 0]);

        $session = DB::table('exam_sessions')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->where('date_completed', null)->first();

        return redirect()->route('exam-session.test', $session->id);
    }

    public function test() {
        return view('exam_session.test');
    }
}
