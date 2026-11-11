<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * get user logged in for profile page
     *
     * @return View
     */
    public function show(): View
    {
        return view('teacher.pages.profile.profile');
    }
}
