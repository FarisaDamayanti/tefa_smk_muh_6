<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class ProfileController extends Controller
{
    /**
     * Show student credential
     *
     * @param Request $request
     * @return View|Factory
     */
    public function index(Request $request)
    {
        $profile = ProfileResource::make(auth_user())->toArray($request);

        return view('student.pages.profile.index', compact('profile'));
    }

}
