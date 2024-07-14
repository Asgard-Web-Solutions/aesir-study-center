<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature;

class PracticeController extends Controller
{
    public function start(Set $set) {
        if (!Feature::active('flash-cards')) {
            abort(404, 'Not found');
        }
        
        return redirect(route('practice-config', $set));
        // return view('practice.start');
    }

    public function config(Set $set) {
        if (!Feature::active('flash-cards')) {
            abort(404, 'Not found');
        }
        
        return view('practice.config')->with([
            'set' => $set,
        ]);
    }
}
