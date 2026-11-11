<?php

namespace App\Http\Resources\Teacher\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamRecapResource extends JsonResource
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
            'exam_date' => $this->exam->exam_date->translatedFormat('j F Y'),
            'exam_title' => $this->exam->title,
            'level_class' => $this->classroom->levelClass->name,
            'subject_name' => $this->exam->questionBank->subject->name,
            'school_year' => $this->exam->schoolYear->school_year,
            'semester' => $this->exam->semester,
            'total_question' => $this->exam->total_questions,
            'passed_student' => $this->exam->passed_students,
            'not_passed_student' => $this->exam->not_passed_students,
        ];
    }
}
