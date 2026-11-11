<?php

namespace App\Http\Resources\Teacher\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamRecapShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'exam_title' => $this->title,
            'level_class' => $this->questionBank->levelClass->name,
            'subject_name' => $this->questionBank->subject->name,
            'school_year' => $this->schoolYear->school_year,
            'semester' => $this->semester,
            'mininum_score' => $this->mininum_score,
            'duration' => $this->duration,
            'total_questions' => $this->total_questions,
            'allow_early_submission' => $this->allow_early_submission ? 'Aktif' : 'Nonaktif',
            'can_view_answer_result' => $this->can_view_answer_result ? 'Aktif' : 'Nonaktif',
            'exam_rules' => $this->rules,
        ];
    }
}
