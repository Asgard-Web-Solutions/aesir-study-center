<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Laravel\Pennant\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function index()
    {
        if (!Feature::active('profile-test-manager')) {
            abort(404, 'Not found');
        }
        $user = $this->getAuthedUser();

        return view('profile.index')->with([
            'user' => $user,
        ]);
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

    public function update(UserRequest $request) {
        $user = $this->getAuthedUser();

        $user->update($request->validated());

        return back()->with('alert', 'Profile Information Saved');
    }

    public function changepass(Request $request) {
        // Validate the form data
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check if the current password matches
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Change the password
        $user = $this->getAuthedUser();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
        
    }

    /** ========== Helper Functions ========== */
    private function getAuthedUser() {
        $user = User::where('id', auth()->user()->id)->with('records')->first();

        return $user;
    }
}
