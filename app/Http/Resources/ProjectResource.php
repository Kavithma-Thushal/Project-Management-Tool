<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $projectTaskStatus = $this->projectTaskStatus;

        return [
            'id' => $this->id,
            'workspace_id' => $this->workspace_id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'privacy_type' => $this->privacy_type,
            'created_at'=> $this->created_at,
            'project_task_statuses' => ProjectTaskStatusResource::collection($projectTaskStatus),
        ];
    }
}
