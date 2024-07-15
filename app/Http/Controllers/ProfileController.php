<?php

namespace App\Http\Controllers;

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
}
