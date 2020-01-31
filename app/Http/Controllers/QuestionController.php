<?php

namespace App\Http\Controllers;

use Alert;
use App\Answer;
use App\Question;
use App\Set;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function exams()
    {
        if (!auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');
            return route()->redirect('home');
        }

        $sets = Set::all();

        return view('manage.sets', [
            'sets' => $sets,
        ]);
    }

    public function index($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');
            return route()->redirect('home');
        }

        $set = Set::find($id);

        return view('manage.questions', [
            'set' => $set,
        ]);
    }

    public function add($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');
            return route()->redirect('home');
        }

        $set = Set::find($id);
        
        return view('manage.addq', [
            'set' => $set,
        ]);
    }

    public function store(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');
            return route()->redirect('home');
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

    public function answers($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');
            return route()->redirect('home');
        }

        $question = Question::find($id);

        return view('manage.answers', [
            'question' => $question,
        ]);
    }

    public function storeAnswer(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');
            return route()->redirect('home');
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

    public function storeExam(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            Alert::toast('Permission Denied', 'warning');
            return route()->redirect('home');
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
