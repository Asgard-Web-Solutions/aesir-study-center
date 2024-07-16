<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\User;
use Illuminate\Http\Request;

class ExamSessionController extends Controller
{
    public function start(Set $examSet) {
        $this->authorize('view', $examSet);

        // Track that the user has taken this exam if they haven't before
        $examSet->records()->syncWithoutDetaching(auth()->user()->id);

        // Get current test sessions
        $session = $examSet->sessions()->wherePivot('date_completed', null)->get();

        if (!$session->count()) {
            // Create a new instance of this test
            $session = $examSet->sessions()->attach(auth()->user()->id);
        }
    }
}
