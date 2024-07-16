<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Set;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ExamSessionConfigurationRequest;
use App\Models\Question;

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

    public function test($sessionId) {
        $session = DB::table('exam_sessions')->where('id', $sessionId)->first();

        // Make sure the session belongs to this user

        $arrayData = json_decode($session->questions_array);
        $examSet = Set::find($session->set_id);
        $question = Question::find($arrayData[$session->current_question]);

        // Generate the answers for this question
        $answers = null;
        $single = null;

        if ($question->answers->count() > 1) {
            $answers = $question->answers->shuffle();
        } else {
            if ($question->group_id) {
                // There was only one answer, so let's grab some more random ones from the test
                $single = true;
                $answers = $question->answers;

                $pool = Question::where('set_id', '=', $question->set_id)->where('group_id', '=', $question->group_id)->where('id', '!=', $question->id)->get();

                $pool = $pool->shuffle();

                $count = 1;

                foreach ($pool as $q) {
                    $answers->push($q->answers->first());
                    $count = $count + 1;

                    if ($count == config('test.target_answers')) {
                        break;
                    }
                }

                $answers = $answers->shuffle();
            } else {
                $answers = $question->answers;
            }
        }

        $correct = 0;
        $order = '';

        foreach ($answers as $answer) {
            if ($order != '') {
                $order .= ',';
            }

            $order .= $answer->id;

            if (! $single && $answer->correct) {
                $correct = $correct + 1;
            }
        }

        $multi = ($correct > 1) ? 1 : 0;

        return view('exam_session.test')->with([
            'question' => $question,
            'answers' => $answers,
            'multi' => $multi,
            'examSet' => $examSet,
            'order' => $order,
            'session' => $session,
        ]);
    }
}
