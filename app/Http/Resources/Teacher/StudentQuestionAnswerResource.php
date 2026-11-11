<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentQuestionAnswerResource extends JsonResource
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
            'question' => $this->examQuestion->question,
            'answer' => $this->answer_text,
            'score' => $this->score,
            'max_score' => $this->examQuestion->score,
            'status' => is_null($this->is_correct) ? 'Belum Dinilai' : 'Sudah Dinilai',
        ];
    }
}
