<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Set;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ExamSessionConfigurationRequest;
use App\Models\Question;
use App\Models\Answer;

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

        return view('exam-session.configure')->with([
            'examSet' => $examSet,
        ]);
    }

    public function store(ExamSessionConfigurationRequest $request, Set $examSet) {
        $this->authorize('view', $examSet);

        // See if there is already an exam in progress
        $session = DB::table('exam_sessions')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->where('date_completed', null)->first();
        if ($session) {
            return redirect()->route('exam-session.test', $examSet);
        }

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

    public function test(Set $examSet) {

        $session = DB::table('exam_sessions')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->where('date_completed', null)->first();

        // If the last question was answered, complete the session
        if (($session->current_question) == ($session->question_count -1)) {
            return redirect()->route('exam-session.summary', $examSet);
        }

        $arrayData = json_decode($session->questions_array);
        $question = Question::find($arrayData[$session->current_question]);

        // Generate the list of answers for this question
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
        $order = array();

        foreach ($answers as $answer) {
            $order[] = $answer->id;

            if (! $single && $answer->correct) {
                $correct = $correct + 1;
            }
        }

        $multi = ($correct > 1) ? 1 : 0;

        return view('exam-session.test')->with([
            'question' => $question,
            'answers' => $answers,
            'multi' => $multi,
            'examSet' => $examSet,
            'order' => json_encode($order),
            'session' => $session,
        ]);
    }

    public function answer(Request $request, Set $examSet) {
        $this->validate($request, [
            'question' => 'required|integer',
            'order' => 'required|string',
        ]);

        $session = DB::table('exam_sessions')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->where('date_completed', null)->first();
        $question = Question::find($request->question);

        $correctAnswersCount = 0;
        $correct = 0;

        foreach ($question->answers as $answer) {
            if ($answer->correct) {
                $correctAnswersCount = $correctAnswersCount + 1;
            }
        }

        $multi = ($correctAnswersCount > 1) ? 1 : 0;

        // An array to store / convert the checkbox answers into so we can compare easier
        $normalizedAnswer = [];

        // An array to store the answer and all results in to make displaying the answers easier
        $testAnswers = [];

        foreach ($question->answers as $answer) {
            if ($multi) {
                $normalizedAnswer[$answer->id] = (array_key_exists($answer->id, $request->answer)) ? 1 : 0;

                if ($answer->correct && ($normalizedAnswer[$answer->id] == 1)) {
                    $correct = $correct + 1;
                    $testAnswers[] = [
                        'id' => $answer->id,
                        'text' => $answer->text,
                        'correct' => $answer->correct,
                        'gotRight' => 1,
                    ];
                } else {
                    $testAnswers[] = [
                        'id' => $answer->id,
                        'text' => $answer->text,
                        'correct' => $answer->correct,
                        'gotRight' => 0,
                    ];
                }
            } elseif ($question->answers->count() == 1) {
                $correct = ($request->answer == $answer->id) ? 1 : 0;

                // If this is a single answer question, then we don't care if the user marked it as correct or not
                // We know that there is only one answer so it must be correct
                // Later this is going to be a "fill in the blank" type question
                $correctAnswersCount = 1;
            } else {
                $normalizedAnswer[$answer->id] = ($request->answer == $answer->id) ? 1 : 0;

                if ($answer->correct && ($request->answer == $answer->id)) {
                    $correct = 1;

                    $testAnswers[] = [
                        'id' => $answer->id,
                        'text' => $answer->text,
                        'correct' => $answer->correct,
                        'gotRight' => 1,
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

        $result = ($correct == $correctAnswersCount) ? 1 : 0;

        DB::table('exam_sessions')->where('id', $session->id)->update([
            'current_question' => $session->current_question +1,
            'correct_answers' => ($result == 1) ? $session->correct_answers + 1 : $session->correct_answers,
            'incorrect_answers' => ($result == 0) ? $session->incorrect_answers + 1 : $session->incorrect_answers,
        ]);

        $userQuestion = DB::table('user_question')->where('user_id', auth()->user()->id)->where('question_id', $question->id)->first();
        
        $updatedScore = ($result == 1) ? $userQuestion->score + config('test.add_score') : $userQuestion->score - config('test.sub_score');
        $updatedScore = ($updatedScore < config('test.min_score')) ? config('test.min_score') : $updatedScore;

        DB::table('user_question')->where('user_id', auth()->user()->id)->where('question_id', $question->id)->update([
            'score' => $updatedScore
        ]);

        // let's make sure they didn't just refresh the page
        // if (! $test->questions->contains($question)) {
        //     $test->questions()->attach($question->id, ['result' => $result]);

        //     $userScore = DB::table('user_question')
        //         ->where('user_id', '=', $user->id)
        //         ->where('question_id', '=', $question->id)
        //         ->select('score')
        //         ->first();

        //     $score = $userScore->score;

        //     if ($result) {
        //         $score = $score + config('test.add_score');
        //     } else {
        //         $score = $score - config('test.sub_score');
        //         if ($score < config('test.min_score')) {
        //             $score = config('test.min_score');
        //         }
        //     }

        //     $now = Carbon::now();
        //     $next = $now->addHours((config('test.hour_multiplier') * ($score ** 2)));

        //     $user->questions()->updateExistingPivot($question->id, ['score' => $score, 'next_at' => $next]);
        // }

        // Now load the answers in the order that they were shown to the user
        $aorder = json_decode($request->order);

        $orderedAnswers = [];

        foreach ($aorder as $answerID) {
            $answer = Answer::find($answerID);

            if ($question->answers->count() > 1) {
                $orderedAnswers[] = [
                    'id' => $answer->id,
                    'text' => $answer->text,
                    'correct' => $answer->correct,
                ];
            } else {
                $orderedAnswers[$answer->id] = [
                    'id' => $answer->id,
                    'text' => $answer->text,
                    'correct' => ($answer->question_id == $question->id) ? 1 : 0,
                ];

                $normalizedAnswer[$answer->id] = ($request->answer == $answer->id) ? 1 : 0;
            }
        }

        return view('exam-session.answer', [
            'question' => $question,
            'answers' => $orderedAnswers,
            'normalizedAnswer' => $normalizedAnswer,
            'result' => $result,
            'multi' => $multi,
            'correct' => $correct,
            'examSet' => $examSet,
            'session' => $session,
        ]);

        return view('exam-session.answer');
    }

    public function summary(Set $examSet) {
        $session = DB::table('exam_sessions')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->where('date_completed', null)->first();

        // Make sure the exam has been completed
        if (($session->current_question) != ($session->question_count -1)) {
            return redirect()->route('exam-session.test', $examSet);
        }

        $updateSession['date_completed'] = Carbon::now();
        $updateSession['grade'] = round(($session->correct_answers / $session->question_count) * 100);
        DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);

        return view('exam-session.summary');
    }
}
