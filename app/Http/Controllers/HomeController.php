<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Set;
use App\Models\Test;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Laravel\Pennant\Feature;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     */
    public function index(): View
    {

        return view('home');
    }

    public function public(): View
    {
        $exams = Set::where('visibility', 1)->get();

        return view('home.public')->with([
            'exams' => $exams,
        ]);
    }

    public function history($id): View
    {
        $user_id = Auth::id();

        $user = User::find($user_id);
        $set = Set::find($id);

        $tests = Test::where('user_id', '=', $user->id)
            ->where('set_id', '=', $id)
            ->orderBy('end_at', 'desc')
            ->get();

        foreach ($tests as $test) {
            $start = new Carbon($test->start_at);
            $diffTime = $start->diffInMinutes($test->getAttributes()['end_at']);
            $test->duration = $diffTime;
        }

        return view('history', [
            'tests' => $tests,
            'set' => $set,
        ]);
    }

    public function colors(): View
    {
        return view('colors');
    }
}
