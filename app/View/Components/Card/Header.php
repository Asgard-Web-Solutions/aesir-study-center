<?php

namespace App\View\Components\Card;

use Illuminate\View\Component;

class Header extends Component
{
    public $text;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card.header');
    }
}
