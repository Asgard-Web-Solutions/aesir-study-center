<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Set;
use Alert;

class SetController extends Controller
{
    // Save a new exam set
    public function store(Request $request): RedirectResponse
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
        $set->user_id = auth()->user()->id;
        $set->save();

        Alert::toast('Exam Added', 'success');

        return redirect()->route('manage-questions', $set->id);
    }
}
