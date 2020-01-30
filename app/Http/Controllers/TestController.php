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

        // Load all questions to the user questions
        foreach ($set->questions as $question) {
            if (!$user->questions->contains($question))
            {
                $user->questions()->attach($question->id, ['score' => 0, 'next_at' => $now, 'set_id' => $set->id]);
            }
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
            return redirect()->route('tests');
        }

        if ($test->questions->count() >= $test->num_questions)
        {
            die("Test complete");
        }

        $now = Carbon::now();

        $question = DB::table('user_question')->where('set_id', '=', $test->set_id)->where('next_at', '<', $now)->get();
        
        $question = $question->random(1);

        $question = Question::find($question[0]->question_id);

        $answers = $question->answers->shuffle();

        $correct = 0;

        foreach ($answers as $answer) {
            if ($answer->correct) {
                $correct = $correct + 1;
            }
        }

        $multi = ($correct > 1) ? 1 : 0;

        return view('test.question', [
            'question' => $question,
            'answers' => $answers,
            'multi' => $multi,
        ]);
    }
}
