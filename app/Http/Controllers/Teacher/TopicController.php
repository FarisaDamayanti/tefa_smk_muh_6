<?php

namespace App\Http\Controllers\Teacher;

use App\Contracts\Repositories\TopicRepository;
use App\Helpers\SemesterHelper;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TopicController extends Controller
{
    public function __construct
    (
        private TopicRepository $topic,
    ){}

    /**
     * index get data by subject, classroom, and auth employee
     *
     * @param Classroom $classroom
     * @param Subject $subject
     * @param Request $request
     * @return View
     */
    public function index(Classroom $classroom, Subject $subject, Request $request): View
    {
        $currentSemester = SemesterHelper::getCurrentSemester();
        $request->merge(['semester' => $request->query('semester', $currentSemester)]);
        $topics = $this->topic->getByEmployeeSubjectClassroom(auth_employee()->id, $subject->id, $classroom->id, $request);
        return view('teacher.pages.topic.index', compact('topics', 'subject', 'classroom', 'currentSemester'));
    }
}
