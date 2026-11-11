<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModuleDetailResource;
use App\Models\Module;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ModuleController extends Controller
{
    /**
     * Display the specified resource.
     * 
     * @param Module $module
     * @return View
     */
    public function create(Request $request): View
    {
        $topicId = $request->get('topic_id');

        if (!$topicId) {
            abort(404, 'Topic ID tidak ditemukan');
        }

        $topic = Topic::with([
            'subject',
            'classroom.levelClass'
        ])->findOrFail($topicId);

        return view('teacher.pages.material.material-create', [
            'topic' => $topic,
            'subject' => $topic->subject,
            'classroom' => $topic->classroom
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module): View
    {
        $module->load([
            'topic.subject',
            'topic.classroom.levelClass'
        ]);

        return view('teacher.pages.material.material-show', [
            'module' => $module,
            'topic' => $module->topic,
            'subject' => $module->topic->subject,
            'classroom' => $module->topic->classroom
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module): View
    {
        $module->load([
            'topic.subject',
            'topic.classroom.levelClass'
        ]);

        return view('teacher.pages.material.material-edit', [
            'module' => $module,
            'topic' => $module->topic,
            'subject' => $module->topic->subject,
            'classroom' => $module->topic->classroom
        ]);
    }
}