<?php

namespace App\Http\Controllers;

use DB;
use App\Enums\Mastery;
use App\Models\Question;
use App\Enums\Visibility;
use App\Models\Answer;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Set as ExamSet;
use Illuminate\Support\Facades\Auth;

class ExamSetController extends Controller
{
    public function view(ExamSet $exam): View
    {
        $this->authorize('view', $exam);

        $examRecord = null;

        if (Auth::check()) {
            $examRecord = DB::table('exam_records')
                            ->where('set_id', $exam->id)
                            ->where('user_id', Auth::id())
                            ->first();
        }

        $mastery = [];
        foreach (Mastery::cases() as $level) {
            $mastery[$level->value] = $level->name;
        }

        $masters = ExamSet::whereHas('records', function ($query) use ($exam) {
            $query->where('exam_records.highest_mastery', '>', Mastery::Proficient->value)->where('exam_records.set_id', $exam->id)->orderBy('exam_records.highest_mastery', 'desc');
        })->with(['records' => function ($query) {
            $query->where('exam_records.highest_mastery', '>', Mastery::Proficient->value);
        }])->get();

        return view('exam.view')->with([
            'exam' => $exam,
            'examRecord' => $examRecord,
            'mastery' => $mastery,
            'masters' => $masters,
        ]);
    }

    public function public(): View
    {
        $exams = ExamSet::where('visibility', 1)->get();

        return view('exam.public')->with([
            'exams' => $exams,
        ]);
    }

    public function edit(ExamSet $exam) {
        $this->authorize('update', $exam);

        $visibility = Visibility::cases();
        $questions = Question::where('set_id', $exam->id)->where('group_id', 0)->get();

        return view('exam.edit', [
            'exam' => $exam,
            'visibilityOptions' => $visibility,
            'questions' => $questions,
        ]);
    }

    public function add(Request $request, ExamSet $exam) {

        $request->validate([
            'question' => 'required|string',
            'answers*' => 'required|string|nullable',
            'correct*' => 'sometimes',
        ]);

        $question = new Question();
        $question->text = $request->question;
        $question->set_id = $exam->id;
        $question->group_id = 0;
        $question->save();

        foreach ($request->answers as $index => $newAnswer) {
            if ($newAnswer) {
                $answer = new Answer();
    
                $answer->question_id = $question->id;
                $answer->text = $newAnswer;
                $answer->correct = (isset($request->correct[$index])) ? 1 : 0;
                $answer->save();
            }
        }

        return redirect()->route('exam.edit', $exam)->with('success', 'Exam question added');
    }
}
