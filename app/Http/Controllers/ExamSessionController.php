<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\User;
use Illuminate\Http\Request;

class ExamSessionController extends Controller
{
    public function start(Set $examSet) {

        
        $examSet->records()->syncWithoutDetaching(auth()->user()->id);
    }
}
