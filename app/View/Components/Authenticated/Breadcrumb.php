<?php

namespace App\View\Components\Authenticated;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public $title;
    public $breadcrumb;
    public $subtitle;
    public function __construct($title = 'Halaman', $breadcrumb = '', $subtitle = '')
    {
        $this->title = $title;
        $this->breadcrumb = $breadcrumb;
        $this->subtitle = $subtitle;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.authenticated.breadcrumb');
    }
}
