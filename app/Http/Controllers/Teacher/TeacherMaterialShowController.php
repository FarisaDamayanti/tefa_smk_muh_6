<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherMaterialShowController extends Controller
{
    /**
     * Display the specified resource.
     */
    // TeacherMaterialShowController.php
    public function show(Module $module): View
    {
        $module->load([
            'topic.subject',
            'topic.classroom.levelClass'
        ]);
        return view('teacher.pages.material.material-show', compact('module'));
    }
}
