<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Set;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function exams()
    {
        if (! auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');

            return redirect()->route('home');
        }

        $sets = Set::all();

        return view('manage.sets', [
            'sets' => $sets,
        ]);
    }

    public function index($id)
    {
        if (! auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');

            return redirect()->route('home');
        }

        $set = Set::find($id);

        return view('manage.questions', [
            'set' => $set,
        ]);
    }

    // Add an question for an exam
    public function add($id)
    {
        if (! auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');

            return redirect()->route('home');
        }

        $set = Set::find($id);

        return view('manage.addq', [
            'set' => $set,
        ]);
    }

    // Save a question for an exam
    public function store(Request $request, $id)
    {
        if (! auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');

            return redirect()->route('home');
        }

        $set = Set::find($id);

        $this->validate($request, [
            'question' => 'required|string',
        ]);

        $question = new Question();

        $question->set_id = $set->id;
        $question->text = $request->question;
        $question->save();

        Alert::toast('Question Added', 'success');

        return redirect()->route('manage-answers', $question->id);
    }

    // List all answers for a question
    public function answers($id)
    {
        if (! auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');

            return redirect()->route('home');
        }

        $question = Question::find($id);

        return view('manage.answers', [
            'question' => $question,
        ]);
    }

    // Save an answer to a question
    public function storeAnswer(Request $request, $id)
    {
        if (! auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');

            return redirect()->route('home');
        }

        $question = Question::find($id);

        $this->validate($request, [
            'answer' => 'required|string',
            'correct' => 'required|integer',
        ]);

        $answer = new Answer();

        $answer->question_id = $question->id;
        $answer->text = $request->answer;
        $answer->correct = $request->correct;
        $answer->save();

        Alert::toast('Answer Added', 'success');

        return redirect()->route('manage-answers', $question->id);
    }

    public function editAnswer($id)
    {
        $answer = Answer::find($id);

        $question = Question::find($answer->question->id);

        return view('manage.editanswer', [
            'answer' => $answer,
            'question' => $question,
        ]);
    }

    public function updateAnswer(Request $request, $id)
    {
        $answer = Answer::find($id);

        $this->validate($request, [
            'answer' => 'required|string',
            'correct' => 'required|integer',
        ]);

        $answer->text = $request->answer;
        $answer->correct = $request->correct;

        $answer->save();
        Alert::toast('Answer Updated', 'success');

        return redirect()->route('manage-answers', $answer->question->id);
    }

    public function deleteAnswer($id)
    {
        $answer = Answer::find($id);

        $question = Question::find($answer->question->id);

        return view('manage.deleteanswer', [
            'answer' => $answer,
            'question' => $question,
        ]);
    }

    public function deleteAnswerConfirm(Request $request, $id)
    {
        $answer = Answer::find($id);

        $question = Question::find($answer->question->id);

        $this->validate($request, [
            'confirm' => 'string',
        ]);

        if ($request->confirm != 'true') {
            Alert::toast('Something Went Wrong', 'error');

            return redirect()->route('manage-answers', $question->id);
        }

        $answer->delete();

        Alert::toast('Answer deleted', 'success');

        return redirect()->route('manage-answers', $question->id);
    }

    // Save a new exam set
    public function storeExam(Request $request)
    {
        if (! auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');

            return redirect()->route('home');
        }

        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $set = new Set();

        $set->name = $request->name;
        $set->description = $request->description;
        $set->save();

        Alert::toast('Exam Added', 'success');

        return redirect()->route('manage-exams');
    }
}
