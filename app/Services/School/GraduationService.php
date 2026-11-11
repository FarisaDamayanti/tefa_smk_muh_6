<?php

namespace App\Services\School;

use App\Contracts\Repositories\GraduationDetailRepository;
use App\Contracts\Repositories\GraduationRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Enums\UploadDiskEnum;
use App\Http\Requests\School\GraduationRequest;
use App\Traits\UploadTrait;
use Exception;

class GraduationService
{
    use UploadTrait;

    public function __construct
    (
        private SchoolYearRepository $schoolYear,
        private GraduationRepository $graduation,
        private GraduationDetailRepository $graduationDetail,
    ){}

    /**
     * logic store or update graduation data
     * 
     * @param GraduationRequest $request
     * @return void
     */
    public function storeOrUpdate(GraduationRequest $request)
    {
        $data = $request->validated();
        $activeSchoolYear = $this->schoolYear->active();
        $graduation = $this->graduation->getBySchoolYear($activeSchoolYear->id);

        if ($graduation && $graduation?->announcement_date->isPast() && $graduation->graduationDetails()->exists()) {
            throw new Exception("Tidak dapat mengubah data karena kelulusan sudah di umumkan", 403);
        }

        if (isset($data['additional_file'])) {
            if ($this->exist($graduation?->additional_file)) {
                $this->remove($graduation->additional_file);
            }

            $data['additional_file'] = $this->upload(UploadDiskEnum::GRADUATE->value, $data['additional_file'], true);
        } else {
            $data['additional_file'] = $graduation?->additional_file;
        }

        if (!$graduation) {
            $data['school_year_id'] = $activeSchoolYear->id;
            $graduation = $this->graduation->store($data);
        } else {
            $this->graduation->update($graduation->id, $data);
        }
    }
}