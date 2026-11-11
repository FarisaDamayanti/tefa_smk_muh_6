<?php

namespace App\View\Components\Authenticated;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ClassroomSubjectCard extends Component
{
    public $classroom;
    public $subject;
    public $schoolYear;
    public $targetUrl;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $classroom,
        $subject,
        $schoolYear,
        $targetUrl
    ) {
        $this->classroom = $classroom;
        $this->subject = $subject;
        $this->schoolYear = $schoolYear;
        $this->targetUrl = $targetUrl;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.authenticated.classroom-subject-card');
    }
}
