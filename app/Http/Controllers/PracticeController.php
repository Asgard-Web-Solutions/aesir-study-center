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

        $this->authorize('view', $exam);

        $session = $this->getPracticeSession($exam);

        if ($session) {
            return redirect()->route('practice.review', $exam);
        }

        ExamFunctions::initiate_questions_for_authed_user($exam);

        $questionArray = $exam->questions->shuffle()->pluck('id');
        
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

        $this->authorize('view', $exam);
        $this->authorize('create', ExamPractice::class);

        $selectMastery = ['All', 'Strong', 'Weak'];
        
        return view('practice.config')->with([
            'exam' => $exam,
        ]);
    }

    public function review(ExamSet $exam) {
        $this->authorize('view', $exam);
        
        $session = $this->getPracticeSession($exam);

        if (!$session) {
            return redirect()->route('practice.start', $exam);
        }

        $this->authorize('view', $session);
        
        $questionArray = json_decode($session->question_order);

        if (!array_key_exists($session->question_index, $questionArray)) {
            return redirect()->route('practice.done', $exam);
        }

        $question = Question::find($questionArray[$session->question_index]);

        if (!$question) {
            // Something happened, the question was probably deleted
            $session->question_count -= 1;
            
            // Remove the offending question from the session
            unset($questionArray[$session->question_index]);

            // Reindex the array
            $questionArray = array_values($questionArray);
            $session->question_order = json_encode($questionArray);
            $session->save();

            return redirect()->route('practice.review', $exam)->with('warning', 'Skipping question that was deleted from the Exam');
        }

        $answers = Answer::where('question_id', $question->id)->where('correct', 1)->get();

        return view('practice.review')->with([
            'exam' => $exam,
            'question' => $question,
            'answers' => $answers,
            'session' => $session,
        ]);
    }

    public function done(ExamSet $exam) {
        $this->authorize('view', $exam);
        
        $session = $this->getPracticeSession($exam);
        $this->authorize('delete', $session);

        if (!$session) {
            return redirect()->route('profile.exams');
        }

        $session->delete();

        return view('practice.done')->with([
            'exam' => $exam,
        ]);
    }

    public function next(ExamSet $exam) {
        $this->authorize('view', $exam);

        $session = $this->getPracticeSession($exam);
        $this->authorize('view', $session);

        if ($session->question_index >= $session->question_count - 1) {
            return redirect()->route('practice.done', $exam);
        }

        $session->update([
            'question_index' => $session->question_index + 1,
        ]);

        return redirect()->route('practice.review', $exam);
    }
    
    public function previous(ExamSet $exam) {
        $this->authorize('view', $exam);

        $session = $this->getPracticeSession($exam);
        $this->authorize('view', $session);

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
