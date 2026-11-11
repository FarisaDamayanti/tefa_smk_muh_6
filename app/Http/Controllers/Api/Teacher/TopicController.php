<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\TopicRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTopicRequest;
use App\Http\Requests\Teacher\UpdateTopicRequest;
use App\Http\Resources\Teacher\TopicResource;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Topic;
use App\Services\Teacher\TopicService;
use App\Traits\PaginationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    use PaginationTrait;

    public function __construct
    (
        private TopicRepository $topic,
        private TopicService $service,
    ){}

    /**
     * index get data by subject, classroom, and auth employee
     * 
     * @param Subject $subject
     * @param Classroom $classroom
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Subject $subject, Classroom $classroom, Request $request): JsonResponse
    {
        try {
            $topic = $this->topic->getByEmployeeSubjectClassroom(auth_employee()->id, $subject->id, $classroom->id, $request);
            $data['data'] = TopicResource::collection($topic);
            $data['paginate'] = $this->customPaginate($topic->currentPage(), $topic->lastPage(), $topic->perPage());

            return ResponseHelper::success($data, message('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }

    /**
     * store data topic
     * 
     * @param StoreTopicRequest $request
     * @param Subject $subject
     * @param Classroom $classroom
     * @return JsonResponse
     */
    public function store(StoreTopicRequest $request, Subject $subject, Classroom $classroom): JsonResponse
    {
        try {
            $data = $this->service->store($request, $subject, $classroom);
            $this->topic->store($data);

            return ResponseHelper::success($data, message('alert.add_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * update data topic
     * 
     * @param UpdateTopicRequest $request
     * @param Topic $topic
     * @return JsonResponse
     */
    public function update(UpdateTopicRequest $request, Topic $topic): JsonResponse
    {
        try {
            $data = $request->validated();
            $this->topic->update($topic->id, $data);

            return ResponseHelper::success($data, message('alert.update_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }

    /**
     * delete data topic
     * 
     * @param Topic $topic
     * @return JsonResponse
     */
    public function destroy(Topic $topic): JsonResponse
    {
        try {
            $this->topic->delete($topic->id);

            return ResponseHelper::success($topic, message('alert.delete_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.delete_failed', $th));
        }
    }
}
