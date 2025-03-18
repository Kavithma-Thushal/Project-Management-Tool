<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource($this['user']),
            'access_token' => $this['access_token']
            // 'authorization' => [
            //     'role_name' => $this['user']->getRoleNames()[0] ?? null,
            //     'permissions' => $this['user']->getPermissionsArray()
            // ],
        ];
    }
}
