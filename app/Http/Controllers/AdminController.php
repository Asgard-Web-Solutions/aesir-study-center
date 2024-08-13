<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function index()
    {
        if (! Gate::allows('isAdmin')) {
            abort(403);
        }

        return view('admin.index');
    }

    public function users()
    {
        if (! Gate::allows('isAdmin')) {
            abort(403);
        }

        $users = User::all();

        return view('admin.users')->with([
            'users' => $users,
        ]);
    }

    public function user(User $user)
    {
        if (! Gate::allows('isAdmin')) {
            abort(403);
        }

        return view('admin.user')->with([
            'user' => $user,
        ]);
    }

    public function gift(Request $request, User $user)
    {
        if (! Gate::allows('isAdmin')) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string',
            'months' => 'required|integer|min:1|max:24',
        ]);

        $user->isMage = 1;
        $user->gift_reason = $request->reason;
        $user->mage_expires_on = Carbon::now()->addMonths($request->months)->format('Y-m-d');
        $user->save();

        return redirect()->route('profile.view', $user)->with('success', 'User was granted Mage status');
    }

    public function userUpdate(UserRequest $request, User $user)
    {
        if (! Gate::allows('isAdmin')) {
            abort(403);
        }

        $user->update($request->validated());

        return redirect()->route('admin.users')->with('alert', 'User updated successfully');
    }
}
