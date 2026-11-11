<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $modules = $this->lessonSchedule
            ->classroom
            ->modules
            ->filter(fn($item) => $item->meeting_number == $this->meeting)
            ->sortBy(['created_at', 'asc']);

        $tasks = $this->lessonSchedule
            ->classroom
            ->tasks
            ->filter(fn($item) => $item->meeting_number == $this->meeting)
            ->sortBy('deadline');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date->translatedFormat('d F Y'),
            'meeting' => $this->meeting,
            'semester' => $this->semester,
            'modules' => ModuleResource::collection($modules)->toArray($request),
            'tasks' => TaskResource::collection($tasks)->toArray($request),
            'lesson_schedule_id' => $this->lesson_schedule_id,
        ];
    }
}
