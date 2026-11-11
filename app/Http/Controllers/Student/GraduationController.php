<?php

namespace App\Http\Controllers\Student;

use App\Contracts\Repositories\GraduationDetailRepository;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class GraduationController extends Controller
{
    public function __construct
    (
        private GraduationDetailRepository $graduationDetail,
    ){}

    public function index()
    {
        $graduationDetail = $this->graduationDetail->getByStudent(auth_student()->id);

        $announcementDate = $graduationDetail->graduation->announcement_date;
        $remainingTime = max((int) now()->diffInSeconds($announcementDate, false), 0);

        return view('student.pages.graduation.index', compact('graduationDetail', 'remainingTime'));
    }

    public function update()
    {
        try {
            $graduationDetail = $this->graduationDetail->getByStudent(auth_student()->id);

            if ($graduationDetail && $graduationDetail?->graduation?->announcement_date->isFuture()) {
                throw new Exception("Pengumuman kelulusan masi belum di umumkan", 403);
            }

            $this->graduationDetail->updateStatusOpenByStudent(auth_student()->id);

            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal membuka hasil kelulusan. ' . $th->getMessage());
        }
    }
}
