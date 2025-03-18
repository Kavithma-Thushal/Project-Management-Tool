<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $createdUser = $this->createdUser;
        $workspaceProjects = $this->workspaceProjects;
        $workspaceSkills = $this->workspaceSkills;

        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'last_name' => $this->last_name,
            'color' => $this->color,
            'created_by' => new UserResource($createdUser),
            'workspace_skills' => SkillResource::collection($workspaceSkills),
            'workspace_projects' => ProjectResource::collection($workspaceProjects),
            'created_at'=> $this->created_at,
        ];
    }
}
