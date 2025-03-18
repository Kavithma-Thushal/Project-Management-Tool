<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\CrudRepository;

class UserRepository extends CrudRepository implements UserRepositoryInterface
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email',$email)->first();
    }

    public function findById($id): ?User
    {
        return User::where('id',$id)->first();
    }

    public function findByNIC(string $nic): ?User
    {
        return User::where('nic',$nic)->first();
    }

    public function deleteByWorkspaceId(int $workspaceId): void
    {
        User::where('selected_workspace_id', $workspaceId)->update(['selected_workspace_id' => null]);
    }
}
