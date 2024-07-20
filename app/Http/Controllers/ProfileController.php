<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Pennant\Feature;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        if (!Feature::active('profile-test-manager')) {
            abort(404, 'Not found');
        }

        return view('profile.index');
    }

    public function exams() {
        $user = $this->getAuthedUser();
        $records = $user->records;

        return view('profile.exams')->with([
            'records' => $records,
        ]);
    }

    public function myexams() {
        $user = $this->getAuthedUser();
        $exams = $user->exams;

        return view('profile.myexams')->with([
            'exams' => $exams,
        ]);
    }

    /** ========== Helper Functions ========== */
    private function getAuthedUser() {
        $user = User::where('id', auth()->user()->id)->with('records')->first();

        return $user;
    }
}
