<?php

namespace App\Http\Controllers;
use Alert;
use App\Models\Set;
use App\Enums\Visibility;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ExamSetDataRequest;

class SetController extends Controller
{
    public function create()
    {
        $this->authorize('create', Set::class);

        $visibility = Visibility::cases();

        return view('set.create')->with([
            'visibility' => $visibility,
        ]);
    }

    // Save a new exam set
    public function store(ExamSetDataRequest $request): RedirectResponse
    {
        $this->authorize('create', Set::class);

        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->user()->id;

        $set = Set::create($validatedData);

        Alert::toast('Exam Set Created', 'success');

        return redirect()->route('manage-questions', $set->id);
    }

    public function update(ExamSetDataRequest $request, Set $set):RedirectResponse
    {
        $this->authorize('update', $set);

        $set->update($request->validated());

        Alert::toast('Exam Updated', 'success');

        return redirect()->route('manage-questions', $set->id);
    }
}
