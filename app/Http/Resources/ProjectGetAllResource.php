<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectGetAllResource extends JsonResource
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
            'workspace_id' => $this->workspace_id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'privacy_type' => $this->privacy_type,
            'created_at'=> $this->created_at,
        ];
    }
}
