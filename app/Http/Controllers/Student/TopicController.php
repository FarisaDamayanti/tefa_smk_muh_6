<?php

namespace App\Http\Controllers\Student;

use App\Contracts\Repositories\EmployeeRepository;
use App\Contracts\Repositories\TopicRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Contracts\Repositories\TeacherSubjectRepository;
use App\Helpers\SemesterHelper;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Employee;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function __construct(
        private TopicRepository $topic,
        private TeacherSubjectRepository $teacherSubject,
    ) {}

    public function index(Request $request, Classroom $classroom, Subject $subject)
    {
        $classroom->loadMissing([
            'employee.user',
        ]);
        $currentSemester = SemesterHelper::getCurrentSemester();
        $request->merge(['semester' =>  $request->query('semester',  $currentSemester)]);
        $topics = $this->topic->getBySubject($request, auth_student()->id, $subject->id, $classroom->id);
        $teacher = $this->teacherSubject->getBySubjectAndClassroom($subject->id, $classroom->id)->employee;
        
        return view('student.pages.topic.index', compact('topics', 'subject', 'classroom', 'teacher', 'currentSemester'));
    }
}