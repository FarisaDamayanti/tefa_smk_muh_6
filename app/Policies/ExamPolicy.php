<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExamPolicy
{
    /**
     * check if user enrolled to exam
     *
     * @param User $user
     * @param Exam $exam
     * @return Response
     */
    public function canParticipate(User $user, Exam $exam): Response
    {
        $isEnrolled = $exam->examClassrooms()
            ->whereHas('classroom.classroomStudents', fn ($q) => $q->where('student_id', $user->student->id))
            ->exists();

        if (!$isEnrolled) {
            return Response::deny('Anda tidak terdaftar di kelas yang mengikuti ujian ini.');
        }

        $now = now();
        $examStartTime = $exam->exam_date;
        $examEndTime = $exam->getExamEndTime();

        if ($now->isBefore($examStartTime)) {
            return Response::deny('Ujian belum dimulai.');
        }

        if ($now->isAfter($examEndTime)) {
            return Response::deny('Ujian sudah berakhir.');
        }

        return Response::allow();
    }

    /**
     * check user can start exam or not, and cannot double exam
     *
     * @param User $user
     * @param Exam $exam
     * @return boolean
     */
    public function canStart(User $user, Exam $exam)
    {
        $canParticipate = $this->canParticipate($user, $exam);
        if (!$canParticipate->allowed()) {
            return $canParticipate;
        }

        $existingSession = $user->student->studentExams()
            ->where('exam_id', $exam->id)
            ->whereIn('status', ['passed', 'not_passed'])
            ->exists();

        if ($existingSession) {
            return Response::deny('Anda sudah mengikuti ujian ini.');
        }

        return Response::allow();
    }

    /**
     * check user can submit or not
     *
     * @param User $user
     * @param Exam $exam
     * @return Response
     */
    public function canAccess(User $user, Exam $exam): Response
    {
        $session = $user->student->studentExams()
            ->where('exam_id', $exam->id)
            ->where('status', 'on_progress')
            ->first();

        if (!$session) {
            return Response::deny('Session ujian tidak ditemukan atau sudah selesai.');
        }

        return Response::allow();
    }
}
