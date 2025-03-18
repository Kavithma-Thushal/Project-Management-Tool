<?php

namespace App\Repositories\ProjectMember;

use App\Models\ProjectMember;
use App\Repositories\CrudRepository;

class ProjectMemberRepository extends CrudRepository implements ProjectMemberRepositoryInterface
{

    public function __construct(ProjectMember $model)
    {
        parent::__construct($model);
    }

    public function deleteByProjectId(int $projectId): void
    {
        $this->model->where('project_id', $projectId)->delete();
    }
}
