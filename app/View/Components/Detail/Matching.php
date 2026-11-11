<?php

namespace App\View\Components\Detail;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Matching extends Component
{
    public $question;
    public $loop;

    /**
     * Create a new component instance.
     */
    public function __construct($question, $loop = null)
    {
        $this->question = $question;
        $this->loop = $loop;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.detail.matching');
    }
}