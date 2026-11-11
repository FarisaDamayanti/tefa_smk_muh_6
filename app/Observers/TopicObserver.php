<?php

namespace App\Observers;

use App\Contracts\Repositories\TopicRepository;
use App\Models\Topic;

class TopicObserver
{
    public function __construct
    (
        private TopicRepository $topic,
    ){}

    /**
     * logic after delete data
     *
     * @param Topic $topic
     * @return void
     */
    public function deleted(Topic $topic): void
    {
        $this->topic->reorderTopicNumber([
            'subject_id' => $topic->subject_id,
            'employee_id' => $topic->employee_id,
            'classroom_id' => $topic->classroom_id,
            'semester' => $topic->semester,
        ]);
    }
}
