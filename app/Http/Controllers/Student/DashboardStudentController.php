<?php

namespace App\Http\Controllers\Student;

use App\Contracts\Interfaces\AttendanceInterface;
use App\Contracts\Interfaces\ClassroomStudentInterface;
use App\Contracts\Repositories\ExamRepository;
use App\Contracts\Repositories\TaskRepository;
use App\Http\Controllers\Controller;
use App\Models\ClassroomStudent;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardStudentController extends Controller
{
    private ClassroomStudentInterface $studentClass;
    private AttendanceInterface $attendance;
    private StudentService $service;
    private ExamRepository $exam;
    private TaskRepository $task;

    public function __construct(ClassroomStudentInterface $studentClass, AttendanceInterface $attendance, StudentService $service, ExamRepository $exam, TaskRepository $task)
    {
        $this->studentClass = $studentClass;
        $this->attendance = $attendance;
        $this->service = $service;
        $this->exam = $exam;
        $this->task = $task;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student_id = auth_student()->id;
        $studentClasses = $this->studentClass->whereStudent($student_id);
        if (!$studentClasses) {
            Auth::logout();
            return redirect('/login')->with('error', 'Akun anda belum ada dalam kelas');
        }
        $single_attendance = $this->attendance->userToday('App\Models\ClassroomStudent', $studentClasses->id);
        $history_attendance = $this->attendance->whereUser($studentClasses->id, 'App\Models\ClassroomStudent');
        $chartAttendance = $this->service->chartAttendance($student_id);
        $chartTask = $this->task->getNotDoneAndDoneTaskCount($studentClasses->classroom_id, $student_id);
        $incomingExams = $this->exam->getInComingExamByStudent($student_id);
        $incomingDeadlineTasks = $this->task->getIncomingDeadlineTasksByStudent($student_id);

        return view('student.pages.dashboard.dashboard', compact(
            'studentClasses',
            'single_attendance',
            'history_attendance',
            'chartAttendance',
            'chartTask',
            'incomingExams',
            'incomingDeadlineTasks'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
