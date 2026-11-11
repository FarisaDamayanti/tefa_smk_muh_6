<?php

namespace App\Http\Resources\Teacher;

use App\Http\Resources\ModuleResource;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'topic_number' => $this->topic_number,
            'modules' => ModuleResource::collection($this->modules),
            'tasks' => TaskResource::collection($this->tasks),
        ];
    }
}
