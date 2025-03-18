<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $userDetails = $this->user;

        return [
            'id' => $this->id,
            'status' => $this->status,
            'is_admin' => $this->is_admin,
            'user_details' => new UserResource($userDetails),
            'created_at'=> $this->created_at,
        ];
    }
}
