<?php

namespace App\Services\School;

use App\Contracts\Repositories\GraduationDetailRepository;
use App\Contracts\Repositories\GraduationRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\StudentRepository;
use App\Enums\GraduationStatusEnum;
use App\Enums\StudentStatusEnum;
use App\Http\Requests\School\GraduationDetailRequest;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GraduationDetailService
{
    public function __construct
    (
        private GraduationDetailRepository $graduationDetail,
        private GraduationRepository $graduation,
        private SchoolYearRepository $schoolYear,
        private StudentRepository $student,
    ){}

    /**
     * logic bulk upsert for student graduation status
     * 
     * @param GraduationDetailRequest $request
     * @return void
     */
    public function storeOrUpdate(GraduationDetailRequest $request): void
    {
        $data = $request->validated();

        $activeSchoolYear = $this->schoolYear->active();

        $graduation = $this->graduation->getBySchoolYear($activeSchoolYear->id);

        if (!$graduation) {
            throw new Exception("Data informasi kelulusan tidak ditemukan", 404);
        }

        if ($graduation->announcement_date->isPast() && $graduation->graduationDetails()->exists()) {
            throw new Exception(
                "Tidak dapat mengubah data karena pengumuman kelulusan sudah diumumkan",
                403
            );
        }

        $existing = $this->graduationDetail->getByGraduation($graduation->id);

        $insertData = [];
        $updates = [];
        $studentStatusUpdates = [];
        $now = now();

        foreach ($data['graduates'] as $graduate) {
            $studentId = $graduate['student_id'];
            $status = $graduate['status'];

            $existingData = $existing->get($studentId);
            $studentStatusUpdates[$studentId] =
                $status === GraduationStatusEnum::PASSED->value
                    ? StudentStatusEnum::PASSED->value
                    : StudentStatusEnum::ACTIVE->value;

            if (!$existingData) {
                $insertData[] = [
                    'id' => (string) Str::uuid(),
                    'graduation_id' => $graduation->id,
                    'student_id' => $studentId,
                    'status' => $graduate['status'],
                    'is_open' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                continue;
            }

            if ($existingData->status !== $status) {
                $updates[$existingData->id] = $status;
            }
        }

        DB::transaction(function () use (
            $insertData,
            $updates,
            $studentStatusUpdates,
            $now
        ) {
            if (!empty($insertData)) {
                $this->graduationDetail->insert($insertData);
            }

            if (!empty($updates)) {
                $this->graduationDetail->bulkUpdateStatus($updates, $now);
            }

            if (!empty($studentStatusUpdates)) {
                $this->student->bulkUpdateStatus($studentStatusUpdates, $now);
            }
        });
    }
}
