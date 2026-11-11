<?php

namespace App\View\Components\Detail;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Essay extends Component
{
    public $question;

    /**
     * Create a new component instance.
     */
    public function __construct($question)
    {
        $this->question = $question;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.detail.essay');
    }
}