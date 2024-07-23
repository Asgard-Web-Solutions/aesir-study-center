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
        $question = Question::find($questionArray[$session->question_index]);
        $answers = Answer::where('question_id', $question->id)->where('correct', 1)->get();

        return view('practice.review')->with([
            'exam' => $exam,
            'question' => $question,
            'answers' => $answers,
        ]);
    }

    public function next(ExamSet $exam) {
        $session = $this->getPracticeSession($exam);

        $session->update([
            'question_index' => $session->question_index + 1,
        ]);

        return redirect()->route('practice.review', $exam);
    }

    /** ========== Helper Functions ========== */
    private function getPracticeSession(ExamSet $exam) {
        $session = ExamPractice::where('exam_id', $exam->id)->where('user_id', auth()->user()->id)->first();

        return $session;
    }
}
