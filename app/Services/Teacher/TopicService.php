<?php

namespace App\Services\Teacher;

use App\Contracts\Repositories\TopicRepository;
use App\Http\Requests\Teacher\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Models\Classroom;
use App\Models\Subject;

class TopicService
{
    public function __construct
    (
        private TopicRepository $topic,
    ){}

    /**
     * mapping data
     * 
     * @param StoreTopicRequest $request
     * @param Subject $subject
     * @param Classroom $classroom
     * @return array
     */
    public function store(StoreTopicRequest $request, Subject $subject, Classroom $classroom): array
    {
        $data = $request->validated();

        $employee = auth_employee();

        $data['classroom_id'] = $classroom->id;
        $data['employee_id'] = $employee->id;
        $data['subject_id'] = $subject->id;

        $data['topic_number'] = $this->topic->getCountWithSpesificParam([
            'subject_id' => $subject->id,
            'employee_id' => $employee->id,
            'classroom_id' => $classroom->id,
            'semester' => $request->semester,
        ]) + 1;

        return $data;
    }
}
