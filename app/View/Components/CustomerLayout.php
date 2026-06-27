<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomerLayout extends Component
{
    public function __construct(public string $title = '') {}

    public function render(): View|Closure|string
    {
        return view('layouts.customer');
    }
}
