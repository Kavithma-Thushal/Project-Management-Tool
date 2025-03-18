<?php

namespace App\Http\Resources;

use App\Models\ProjectTaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $taskStatus = $this->status;

        return [
            'id' => $this->id,
            'parent_task_id' => $this->parent_task_id,
            'project_id' => $this->project_id,
            'task_type' => $this->task_type_id,
            'assignee_id' => $this->assignee_id,
            'task_status' => new ProjectTaskStatusResource($taskStatus),
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'estimated_hours' => $this->estimated_hours,
            'spent_hours' => $this->spent_hours,
            'media' => TaskMediaResource::collection($this->taskMedia),
            'task_timeline' => TaskTimelineResource::collection($this->taskTimelines()->orderBy('created_at', 'desc')->get()),
            'task_work_log' => TaskWorkLogResource::collection($this->taskWorkLogs),
        ];
    }
}
