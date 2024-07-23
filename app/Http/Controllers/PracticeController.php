<?php

namespace App\Http\Controllers;

use App\Helpers\ExamFunctions;
use App\Models\Set as ExamSet;
use App\Models\ExamPractice;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature;
use App\Models\Question;
use App\Models\Answer;
use DB;

class PracticeController extends Controller
{
    public function start(ExamSet $exam) {
        if (!Feature::active('flash-cards')) {
            abort(404, 'Not found');
        }

        $session = $this->getPracticeSession($exam);

        if ($session) {
            return redirect()->route('practice.review', $exam);
        }

        ExamFunctions::initiate_questions_for_authed_user($exam);

        $questionArray = $exam->questions->pluck('id');
        
        $practice = ExamPractice::create([
            'user_id' => auth()->user()->id,
            'exam_id' => $exam->id,
            'question_count' => $exam->questions->count(),
            'question_index' => 0,
            'question_order' => json_encode($questionArray),
        ]);

        return redirect()->route('practice.review', $exam);
    }

    public function config(ExamSet $exam) {
        if (!Feature::active('flash-cards')) {
            abort(404, 'Not found');
        }

        $selectMastery = ['All', 'Strong', 'Weak'];
        
        return view('practice.config')->with([
            'exam' => $exam,
        ]);
    }

    public function review(ExamSet $exam) {
        $session = $this->getPracticeSession($exam);

        $questionArray = json_decode($session->question_order);

        if (!array_key_exists($session->question_index, $questionArray)) {
            return redirect()->route('practice.done', $exam);
        }

        $question = Question::find($questionArray[$session->question_index]);
        $answers = Answer::where('question_id', $question->id)->where('correct', 1)->get();

        return view('practice.review')->with([
            'exam' => $exam,
            'question' => $question,
            'answers' => $answers,
            'session' => $session,
        ]);
    }

    public function done(ExamSet $exam) {
        
        $session = $this->getPracticeSession($exam);

        if (!$session) {
            return redirect()->route('profile.exams');
        }

        $session->delete();

        return view('practice.done')->with([
            'exam' => $exam,
        ]);
    }

    public function next(ExamSet $exam) {
        $session = $this->getPracticeSession($exam);

        if ($session->question_index >= $session->question_count - 1) {
            return redirect()->route('practice.done', $exam);
        }

        $session->update([
            'question_index' => $session->question_index + 1,
        ]);

        return redirect()->route('practice.review', $exam);
    }
    
    public function previous(ExamSet $exam) {
        $session = $this->getPracticeSession($exam);

        if ($session->question_index == 0) {
            return redirect()->route('practice.review', $exam);
        }

        $session->update([
            'question_index' => $session->question_index - 1,
        ]);

        return redirect()->route('practice.review', $exam);
    }
    

    /** ========== Helper Functions ========== */
    private function getPracticeSession(ExamSet $exam) {
        $session = ExamPractice::where('exam_id', $exam->id)->where('user_id', auth()->user()->id)->first();

        return $session;
    }
}
