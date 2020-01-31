<?php

namespace App\Http\Controllers;

use Alert;
use App\Question;
use App\Set;
use App\Test;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function sets()
    {
        $sets = Set::all();

        return view('test.select', [
            'sets' => $sets,
        ]);
    }

    public function select($id)
    {
        $set = Set::find($id);

        return view('test.start', [
            'set' => $set,
        ]);
    }

    public function start(Request $request, $id)
    {
        $set = Set::find($id);
        $user = Auth::user();

        $this->validate($request, [
            'number_questions' => 'required|integer|max:' . $set->questions->count(),
        ]);

        $now = new Carbon();
        $start = $now->subMinutes(2);

        // Load all questions to the user questions
        foreach ($set->questions as $question) {
            if (!$user->questions->contains($question))
            {
                $user->questions()->attach($question->id, ['score' => 0, 'next_at' => $start, 'set_id' => $set->id]);
            }
        }

        // Make sure the user has questions left in the pool
        $questions = DB::table('user_question')->where('set_id', '=', $set->id)->where('next_at', '<=', $now)->get();

        if ($questions->count() < $request->number_questions) {
            Alert::warning('No more quesitons available. Please come back later.');
            return redirect()->route('home');
        }

        $test = new Test();
        $test->user_id = $user->id;
        $test->set_id = $set->id;
        $test->num_questions = $request->number_questions;
        $test->start_at = $now;
        $test->result = 0;
        $test->save();

        return redirect()->route('take-test', $test->id);
    }

    public function test($id)
    {
        $test = Test::find($id);
        $user = Auth::user();

        if ($user->id != $test->user_id) {
            Alert::toast('Invalid Test! Don\'t be a hacker.', 'warning');
            return redirect()->route('tests');
        }

        if ($test->ends_at) {
            Alert::toast('Test Completed', 'warning');
            return redirect()->route('home');
        }

        if ($test->questions->count() >= $test->num_questions)
        {
            $now = Carbon::now();
            $test->end_at = $now;

            $questions = $test->questions;
            $correct = 0;
            foreach($questions as $question) {
                $result = DB::table('test_question')
                    ->where('test_id', '=', $test->id)
                    ->where('question_id', '=', $question->id)
                    ->select('result')
                    ->first();

                $correct = $correct + $result->result;
            }

            $grade = ($correct / $test->num_questions) * 100;
            $test->result = $grade;

            $test->save();
            
            Alert::success("Test Completed!<br />Your Grade: " . $grade . "%");
            return redirect()->route('home');
        }

        $now = Carbon::now();

        $selecting = true;
        while($selecting) {
            $question = DB::table('user_question')->where('set_id', '=', $test->set_id)->where('next_at', '<', $now)->get();
            
            $question = $question->random(1);

            $previous = DB::table('test_question')
                ->where('test_id', '=', $test->id)
                ->where('question_id', '=', $question[0]->question_id)
                ->first();

            if (!$previous)
            {
                $selecting = false;
            }
        }

        $question = Question::find($question[0]->question_id);

        $answers = $question->answers->shuffle();

        $correct = 0;
        $order = "";

        foreach ($answers as $answer) {
            $order .= $answer->id . ",";

            if ($answer->correct) {
                $correct = $correct + 1;
            }
        }

        $multi = ($correct > 1) ? 1 : 0;

        return view('test.question', [
            'question' => $question,
            'answers' => $answers,
            'multi' => $multi,
            'test' => $test,
            'order' => $order,
        ]);
    }

    public function answer(Request $request, $id)
    {
        $test = Test::find($id);
        $user = Auth::user();

        if ($user->id != $test->user_id) {
            Alert::toast('Invalid Test! Don\'t be a hacker.', 'warning');
            return redirect()->route('tests');
        }

        $this->validate($request, [
            'question' => 'required|integer',
        ]);

        $question = Question::find($request->question);
        $correctAnswer = 0;

        foreach ($question->answers as $answer) {
            if ($answer->correct) {
                $correctAnswer = $correctAnswer + 1;
            }
        }

        $multi = ($correctAnswer > 1) ? 1 : 0;
        $correct = 0;

        // An array to store / convert the checkbox answers into so we can compare easier
        $normalizedAnswer = array();

        // An array to store the answer and all results in to make displaying the correct answer easier
        $testAnswers = array();
        
        // Get the answer order from the hidden form field, but how to sort the answers by that?
        //$order = explode(',', $request->order);

        foreach ($question->answers as $answer) {
            if ($multi) {

                $normalizedAnswer[$answer->id] = (array_key_exists($answer->id, $request->answer)) ? 1 : 0;

                if ($answer->correct && ($normalizedAnswer[$answer->id] == 1)) {
                    $correct = $correct + 1;
                    $testAnswers[] = [
                        'id' => $answer->id,
                        'text' => $answer->text,
                        'correct' => $answer->correct,
                        'gotRight' => 1
                    ];
                } else {
                    $testAnswers[] = [
                        'id' => $answer->id,
                        'text' => $answer->text,
                        'correct' => $answer->correct,
                        'gotRight' => 0
                    ];
                }
            } else {
                $normalizedAnswer[$answer->id] = ($request->answer == $answer->id) ? 1 : 0;

                if ($answer->correct && ($request->answer == $answer->id)) {
                    $correct = 1;

                    $testAnswers[] = [
                        'id' => $answer->id,
                        'text' => $answer->text,
                        'correct' => $answer->correct,
                        'gotRight' => 1
                    ];

                } else {
                    $testAnswers[] = [
                        'id' => $answer->id,
                        'text' => $answer->text,
                        'correct' => $answer->correct,
                        'gotRight' => ($request->answer == $answer->id) ? 0 : 1,
                    ];
                }
            }
        }

        $result = ($correct == $correctAnswer) ? 1 : 0;

        // let's make sure they didn't just refresh the page
        if (!$test->questions->contains($question)) {
            $test->questions()->attach($question->id, ['result' => $result]);

            $userScore = DB::table('user_question')
                ->where('user_id', '=', $user->id)
                ->where('question_id', '=', $question->id)
                ->select('score')
                ->first();

            $score = $userScore->score;

            if ($result) {
                $score = $score + 1;
            } else {
                $score = $score - 1;
                if ($score < config('test.min_score')) {
                    $score = config('test.min_score');
                }
            }

            $now = Carbon::now();
            $next = $now->addHours((3 * ($score ** 2)));

            $user->questions()->updateExistingPivot($question->id, ['score' => $score, 'next_at' => $next]);
        }

        return view('test.answer', [
            'test' => $test,
            'question' => $question,
            'answers' => $testAnswers,
            'normalizedAnswer' => $normalizedAnswer,
            'result' => $result,
            'multi' => $multi,
        ]);
    }
}
