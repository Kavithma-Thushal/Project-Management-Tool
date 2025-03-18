<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\CrudRepositoryInterface;

interface UserRepositoryInterface extends CrudRepositoryInterface
{
    public function findById($id): ?User;

    public function findByEmail(string $email): ?User;

    public function deleteByWorkspaceId(int $workspaceId): void;
}
