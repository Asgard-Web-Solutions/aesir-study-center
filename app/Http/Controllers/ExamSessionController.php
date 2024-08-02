<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Set;
use App\Models\User;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Helpers\ExamFunctions;
use App\Http\Requests\ExamSessionConfigurationRequest;
use Illuminate\Support\Facades\Validator;

class ExamSessionController extends Controller
{
    public function start(Set $examSet) {
        $this->authorize('view', $examSet);

        $record = DB::table('exam_records')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->first();
        if (!$record) {
            DB::table('exam_records')->insert([
                'user_id' => auth()->user()->id,
                'set_id' => $examSet->id,
            ]);
        }

        $session = $this->getInProgressSession($examSet);

        if (!$session) {
            return redirect()->route('exam-session.configure', $examSet);
        } else {
            return redirect()->route('exam-session.test', $examSet);
        }
    }

    public function configure(Set $examSet) {
        $this->authorize('view', $examSet);

        $now = Carbon::now();
        $maxQuestions = DB::table('user_question')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->where('next_at', '<', $now)->count();

        if ($maxQuestions == 0) {
            $totalQuestions = DB::table('user_question')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->count();

            if ($totalQuestions == 0) {
                // There are no questions at all, so let's set this to the max available
                $maxQuestions = $examSet->questions->count();
            }
        }

        if ($maxQuestions == 0) {
            return redirect()->route('profile.exams')->with('warning', 'You do not have any available questions to take yet');
        }

        return view('exam-session.configure')->with([
            'examSet' => $examSet,
            'maxQuestions' => $maxQuestions,
        ]);
    }

    public function store(ExamSessionConfigurationRequest $request, Set $examSet) {
        $this->authorize('view', $examSet);

        $now = Carbon::now();
        $maxQuestions = DB::table('user_question')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->where('next_at', '<', $now)->count();

        if ($maxQuestions == 0) {
            // if zero, why?
            $totalQuestions = DB::table('user_question')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->count();

            if ($totalQuestions == 0) {
                // There are no questions at all, so let's set this to the max available
                $maxQuestions = $examSet->questions->count();
            }
        }

        $request->validate([
            'question_count' => 'max:' . $maxQuestions,
        ]);

        if ($request->question_count > $maxQuestions) {
            return back()->with('error', 'Requested question count exceeds maximum available of ' . $maxQuestions . ' Questions.');
        }

        // See if there is already an exam in progress
        $session = $this->getInProgressSession($examSet);
        if ($session) {
            return redirect()->route('exam-session.test', $examSet);
        }

        // Initiate questions for the user
        ExamFunctions::initiate_questions_for_authed_user($examSet);
        $now = Carbon::now();
        $userId = auth()->user()->id;

        // Select number of questions requested
        $questions = DB::table('user_question')->where('user_id', $userId)->where('set_id', $examSet->id)->where('next_at', '<', $now)->get();

        // Shuffle and select the appropriate number of questions
        $questions = $questions->random($request->question_count);
        $questionArray = array();
        $questionArray = $questions->pluck('question_id');

        // Create a new instance of this test
        $examSet->sessions()->attach($userId, ['question_count' => $request->question_count, 'questions_array' => json_encode($questionArray), 'current_question' => 0]);

        return redirect()->route('exam-session.test', $examSet->id);
    }

    public function test(Set $examSet) {
        $session = $this->getInProgressOrLatestSession($examSet);

        // If the last question was answered, complete the session
        if (($session->date_completed) || ($session->current_question == $session->question_count)) {
            return redirect()->route('exam-session.summary', $examSet);
        }

        $arrayData = json_decode($session->questions_array);

        if (!array_key_exists($session->current_question, $arrayData)) {
            return redirect()->route('exam-session.summary', $examSet);
        }

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

    public function answerRedirect(Set $examSet) {
        return redirect()->route('exam-session.test', $examSet);
    }

    public function answer(Request $request, Set $examSet) {
        $this->validate($request, [
            'question' => 'required|integer',
            'order' => 'required|string',
        ]);

        $session = $this->getInProgressOrLatestSession($examSet);
        $question = Question::find($request->question);

        $recordAnswer = true;
        $questionArray = json_decode($session->questions_array);
        if (($session->current_question == $session->question_count) || ($questionArray[$session->current_question] != $question->id)) {
            // We have already recorded the results of this question, so let's not record it again.
            $recordAnswer = false;
        }

        $correct = 0;
        $correctAnswersCount = $this->determineCorrectAnswerCount($question);
        $multi = ($correctAnswersCount > 1) ? 1 : 0; // Is this a question with multiple correct answers?L
        $normalizedAnswer = [];
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

        $userQuestion = DB::table('user_question')->where('user_id', auth()->user()->id)->where('question_id', $question->id)->first();
        
        if ($recordAnswer) {
            $updatedScore = ($result == 1) ? $userQuestion->score + config('test.add_score') : $userQuestion->score - config('test.sub_score');
            $updatedScore = ($updatedScore < config('test.min_score')) ? config('test.min_score') : $updatedScore;
            $nextAt = Carbon::now()->addHours((config('test.hour_multiplier') * ($updatedScore ** 2)));

            DB::table('user_question')->where('user_id', auth()->user()->id)->where('question_id', $question->id)->update([
                'score' => $updatedScore,
                'next_at' => $nextAt,
            ]);

            // Update mastery for leveled up questions
            $updateMastery = array();
            if ($result == 1) {
                switch($updatedScore) {
                    case config('test.grade_apprentice'):
                        $updateMastery['mastery_apprentice_change'] = $session->mastery_apprentice_change + 1;
                        break;

                    case config('test.grade_familiar'):
                        $updateMastery['mastery_familiar_change'] = $session->mastery_familiar_change + 1;
                        break;

                    case config('test.grade_proficient'):
                        $updateMastery['mastery_proficient_change'] = $session->mastery_proficient_change + 1;
                        break;

                    case config('test.grade_mastered'):
                        $updateMastery['mastery_mastered_change'] = $session->mastery_mastered_change + 1;
                        break;
                }
            } else {
                switch($updatedScore) {
                    case (config('test.grade_apprentice') - 1):
                        $updateMastery['mastery_apprentice_change'] = $session->mastery_apprentice_change - 1;
                        break;

                    case (config('test.grade_familiar') - 1):
                        $updateMastery['mastery_familiar_change'] = $session->mastery_familiar_change - 1;
                        break;

                    case (config('test.grade_proficient') - 1):
                        $updateMastery['mastery_proficient_change'] = $session->mastery_proficient_change - 1;
                        break;

                    case (config('test.grade_mastered') - 1):
                        $updateMastery['mastery_mastered_change'] = $session->mastery_mastered_change - 1;
                        break;
                }
            }

            $updateSession = [
                'current_question' => $session->current_question +1,
                'correct_answers' => ($result == 1) ? $session->correct_answers + 1 : $session->correct_answers,
                'incorrect_answers' => ($result == 0) ? $session->incorrect_answers + 1 : $session->incorrect_answers,
            ];

            if (count($updateMastery)) {
                $updateSession = array_merge($updateSession, $updateMastery);
            }

            DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);
        }

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

        // Get an updated copy of the session
        $session = $this->getSessionById($session->id);
        $userQuestion = DB::table('user_question')->where('user_id', auth()->user()->id)->where('question_id', $question->id)->first();

        return view('exam-session.answer', [
            'question' => $question,
            'answers' => $orderedAnswers,
            'normalizedAnswer' => $normalizedAnswer,
            'result' => $result,
            'multi' => $multi,
            'correct' => $correct,
            'examSet' => $examSet,
            'session' => $session,
            'userQuestionStats' => $userQuestion,
        ]);

        return view('exam-session.answer');
    }

    public function summary(Set $examSet) {
        $session = $this->getInProgressOrLatestSession($examSet);
    
        // Make sure the exam has been completed
        if (($session->current_question) != ($session->question_count)) {
            return redirect()->route('exam-session.test', $examSet);
        }

        if (!$session->date_completed) {
            $dateNow = Carbon::now();
            $updateSession['date_completed'] = $dateNow;
            $updateSession['grade'] = round(($session->correct_answers / $session->question_count) * 100);
            DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);

            $session = $this->getSessionById($session->id);

            // Recalculate Exam Record Stats
            $this->calculateExamRecordStats($examSet);
        }

        $examRecord = DB::table('exam_records')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->first();

        return view('exam-session.summary')->with([
            'examSet' => $examSet,
            'session' => $session,
            'examRecord' => $examRecord,
        ]);
    }


    /** ========== Public Helper Functions ========== */
    public function calculateExamRecordStats(Set $examSet, $user = null) {
        if (!$user) {
            $user = User::find(auth()->user()->id);
        }

        $record = DB::table('exam_records')->where('user_id', $user->id)->where('set_id', $examSet->id)->first();

        $recentSessions = DB::table('exam_sessions')->where('set_id', $examSet->id)->where('user_id', $user->id)->orderBy('date_completed', 'desc')->limit(config('count_tests_for_average_score'))->get();
        $averageCount = 0;
        $averageTotal = 0;
        $dateNow = null;
        foreach($recentSessions as $recentSession) {
            if (!$dateNow) {
                $dateNow = $recentSession->date_completed;
            }

            $averageCount ++;
            $averageTotal += $recentSession->grade;
        }

        $masteryLevelMastered = 0;
        $masteryLevelProficient = 0;
        $masteryLevelFamiliar = 0;
        $masteryLevelApprentice = 0;
        $questions = DB::table('user_question')->where('user_id', $user->id)->where('set_id', $examSet->id)->get();
        
        foreach($questions as $question) {
            if ($question->score >= config('test.grade_mastered')) {
                $masteryLevelMastered ++;
                $masteryLevelProficient ++;
                $masteryLevelFamiliar ++;
                $masteryLevelApprentice ++;

            } elseif ($question->score >= config('test.grade_proficient')) {
                $masteryLevelProficient ++;
                $masteryLevelFamiliar ++;
                $masteryLevelApprentice ++;
            
            } elseif ($question->score >= config('test.grade_familiar')) {
                $masteryLevelFamiliar ++;
                $masteryLevelApprentice ++;
            
            } elseif ($question->score >= config('test.grade_apprentice')) {
                $masteryLevelApprentice ++;
            }
        }

        DB::table('exam_records')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->update([
            'times_taken' => $record->times_taken + 1,
            'recent_average' => round($averageTotal / $averageCount),
            'last_completed' => $dateNow,
            'mastery_apprentice_count' => $masteryLevelApprentice,
            'mastery_familiar_count' => $masteryLevelFamiliar,
            'mastery_proficient_count' => $masteryLevelProficient,
            'mastery_mastered_count' => $masteryLevelMastered,
        ]);
    }

    /** ========== HELPER FUNCTIONS ========== */
    private function getInProgressOrLatestSession($examSet) {
        $session = DB::table('exam_sessions')
        ->where('user_id', auth()->user()->id)
        ->where('set_id', $examSet->id)
        ->orderByRaw('date_completed IS NULL DESC')
        ->orderByDesc('date_completed')
        ->first();

        return $session;
    }

    private function getInProgressSession($examSet) {
        $session = DB::table('exam_sessions')->where('user_id', auth()->user()->id)->where('set_id', $examSet->id)->where('date_completed', null)->first();
        
        return $session;
    }

    private function getSessionById($sessionId) {
        $session = DB::table('exam_sessions')->where('id', $sessionId)->first();
        
        return $session;
    }

    private function determineCorrectAnswerCount($question) {
        $correctAnswersCount = 0;

        foreach ($question->answers as $answer) {
            if ($answer->correct) {
                $correctAnswersCount = $correctAnswersCount + 1;
            }
        }

        return $correctAnswersCount;
    }
}
