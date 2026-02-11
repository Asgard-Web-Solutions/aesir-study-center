<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\Mastery;
use App\Models\Product;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature;
use App\Models\CreditHistory;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Actions\User\ApplyProductToUser;

class ProfileController extends Controller
{
    public function index()
    {
        $user = $this->getAuthedUser();

        return view('profile.index')->with([
            'user' => $user,
        ]);
    }

    public function exams()
    {
        $user = $this->getAuthedUser();
        $records = $user->records()->orderBy('last_completed', 'desc')->get();

        $mastery = [];
        foreach (Mastery::cases() as $level) {
            $mastery[$level->value] = $level->name;
        }

        return view('profile.exams')->with([
            'records' => $records,
            'mastery' => $mastery,
        ]);
    }

    public function myexams()
    {
        $user = $this->getAuthedUser();
        $exams = $user->exams;

        return view('exam.index')->with([
            'exams' => $exams,
        ]);
    }

    public function update(UserRequest $request)
    {
        $user = $this->getAuthedUser();

        $validatedValues = $request->validated();

        if (! $request->has('showTutorial')) {
            $validatedValues['showTutorial'] = 0;
        }

        $user->update($validatedValues);

        return back()->with('success', 'Profile Information Saved');
    }

    public function changepass(Request $request)
    {
        // Validate the form data
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check if the current password matches
        if (! Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Change the password
        $user = $this->getAuthedUser();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

    public function view(User $user)
    {
        $user->load([
        'records' => function ($query) {
            $query->orderBy('exam_records.highest_mastery', 'desc');
        },
        ]);

        $mastery = [];
        foreach (Mastery::cases() as $level) {
            $mastery[$level->value] = $level->name;
        }

        return view('profile.view')->with([
            'user' => $user,
            'mastery' => $mastery,
        ]);
    }

    public function credits(User $user)
    {
        if (! Feature::active('mage-upgrade')) {
            abort(404, 'Not found');
        }

        $history = $user->creditHistory();
        $products = Product::all();

        return view('profile.credits')->with([
            'history' => $history,
            'user' => $user,
            'products' => $products,
        ]);
    }

    public function gift(Request $request, User $user)
    {
        if (! Feature::active('mage-upgrade')) {
            abort(404, 'Not found');
        }

        $request->validate([
            'package' => 'required|integer|exists:products,id',
            'reason' => 'required|string|min:3|max:255',
        ]);

        $package = Product::find($request->package);

        if (!$package) {
            return back()->with('error', 'There was an error retrieving the requested product package');
        }

        ApplyProductToUser::execute($user, $package, "Keeper's Gift", $request->reason);

        return back()->with('success', 'Credits gifted to user!');
    }
}
