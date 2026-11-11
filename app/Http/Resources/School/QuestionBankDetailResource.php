<?php

namespace App\Http\Resources\School;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'subject' => $this->subject->only(['id', 'name']),
            'question_limit' => $this->question_limit,
            'total_questions' => $this->total_questions,
            'total_inactive_questions' => $this->total_inactive_questions,
            // 'questions' => ExamQuestionWithEmployeeResource::collection($this->examQuestions)
        ];
    }
}
