<?php

namespace App\Http\Controllers;

use App\Models\ModelHasRfid;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $role = auth()->user()->roles->pluck('name')->first();

        if ($role == 'school') {
            return to_route('school.index');
        } elseif ($role == 'student') {
            return to_route('student.dashboard');
        } elseif ($role == 'teacher') {
            return to_route('teacher.dashboard');
        } else if (auth()->user()->roles->pluck('name')[0] == 'staff') {
            return to_route('employee.dashboard');
        } else {
            return to_route('beranda');
        }
    }

    public function cek_rfid()
    {
        dd(ModelHasRfid::where('model_type', null)->where('model_id', null)->get());
    }
}
