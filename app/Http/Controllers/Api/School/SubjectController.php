<?php

namespace App\Http\Controllers\Api\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contracts\Repositories\ReligionRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Helpers\ResponseHelper;
use App\Models\Subject;
use App\Http\Requests\Api\StoreSubjectRequest;
use App\Http\Requests\Api\UpdateSubjectRequest;
use App\Http\Requests\SubjectRequest;
use App\Http\Resources\School\SubjectResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SubjectController extends Controller
{
    public function __construct(
        private SubjectRepository $subject,
        private ReligionRepository $religions
    ) {}

    /**
     * get all subject
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $subjects = $this->subject->get();

            $data = SubjectResource::collection($subjects);
            return ResponseHelper::success($data, message('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectRequest $request)
    {
        try {
            if ($this->subject->duplicate($request->name)) {
                return ResponseHelper::error('Mata Pelajaran sudah tersedia');
            }
            $data = $this->subject->store($request->validated());
            return ResponseHelper::success($data, 'Berhasil menambahkan mata pelajaran');
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        try {
            $data = $request->validated();
            if ($subject->religion_id == $data['religion_id'] && $subject->name == $data['name']) return ResponseHelper::success(null, 'Mata Pelajaran Sudah Tersedia', Response::HTTP_BAD_REQUEST);
            $this->subject->update($subject->id, $data);
            return ResponseHelper::success(null, 'Berhasil memperbarui mata pelajaran');
        } catch (\Throwable $th) {
            return ResponseHelper::error( 'Terjadi kesalahan. '.$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        try {
            $this->subject->delete($subject->id);
            return ResponseHelper::success(null, 'Berhasil menghapus mata pelajaran');
        } catch (\Throwable $th) {
            return ResponseHelper::error( 'Terjadi kesalahan. '.$th->getMessage());
        }
    }
}

