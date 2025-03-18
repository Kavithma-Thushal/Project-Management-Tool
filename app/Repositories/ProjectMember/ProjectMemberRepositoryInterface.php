<?php

namespace App\Repositories\ProjectMember;

use App\Repositories\CrudRepositoryInterface;

interface ProjectMemberRepositoryInterface extends CrudRepositoryInterface
{
    public function deleteByProjectId(int $projectId): void;
}
