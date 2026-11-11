<?php

namespace App\Http\Controllers\Api\Student;

use App\Contracts\Repositories\TopicRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Student\TopicResource;
use App\Models\Subject;
use App\Traits\PaginationTrait;
use Illuminate\Http\Request;
use Throwable;

class TopicController extends Controller
{
    use PaginationTrait;

    public function __construct(
        private TopicRepository $topic
    ){}

    public function index(Request $request, Subject $subject)
    {
        $data = [];

        try{

            $topic = $this->topic->getBySubject($request, auth_student()->id, $subject->id);
            $data['data'] = TopicResource::collection($topic);
            $data['paginate'] = $this->customPaginate($topic->currentPage(), $topic->lastPage(), $topic->perPage());

            return ResponseHelper::success($data, message('alert.fetch_success'));
        } catch (Throwable $th){
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }
}
