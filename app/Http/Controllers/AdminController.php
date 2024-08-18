<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Product;
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

    public function userUpdate(UserRequest $request, User $user)
    {
        if (! Gate::allows('isAdmin')) {
            abort(403);
        }

        $user->update($request->validated());

        return redirect()->route('admin.users')->with('alert', 'User updated successfully');
    }

    public function productsList() {
        if (! Gate::allows('isAdmin')) {
            abort(403);
        }
        
        $products = Product::all();

        return view('admin.products')->with([
            'products' => $products,
        ]);
    }
}
