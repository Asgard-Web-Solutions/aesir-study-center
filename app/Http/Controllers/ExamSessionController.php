<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\User;
use Illuminate\Http\Request;

class ExamSessionController extends Controller
{
    public function start(Set $examSet) {

        // Track that the user has taken this exam if they haven't before
        $examSet->records()->syncWithoutDetaching(auth()->user()->id);

        // Create a new instance of this test
        $examSet->sessions()->attach(auth()->user()->id);
    }
}
