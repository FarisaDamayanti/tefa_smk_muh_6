<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskSubmissionResource extends JsonResource
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
            'user_name' => $this->user_name,
            'classroom_name' => $this->classroom_name,
            'grade' => $this->grade,
            'note' => $this->note,
            'answer' => $this->answer,
            'file' => $this->file,
            'submit_date' => $this->submit_date?->translatedFormat('d F Y H.i'),
            'status' => $this->status,
        ];
    }
}
