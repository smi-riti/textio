<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Loader extends Component
{
    public $message;

    public function __construct($message = null)
    {
        $this->message = $message;
    }

    public function render()
    {
        return view('components.loader');
    }
}