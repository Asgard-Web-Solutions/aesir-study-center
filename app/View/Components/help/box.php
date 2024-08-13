<?php

namespace App\View\Components\help;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class box extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $user = Auth::user();

        if ($user && $user->showTutorial) {
            return view('components.help.box');
        }

        return '';
    }
}
