<?php

namespace App\Http\Controllers;

use App\Models\Set as ExamSet;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature;

class PracticeController extends Controller
{
    public function start(ExamSet $exam) {
        if (!Feature::active('flash-cards')) {
            abort(404, 'Not found');
        }
        
        return redirect(route('practice.config', $exam));
        // return view('practice.start');
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
}
