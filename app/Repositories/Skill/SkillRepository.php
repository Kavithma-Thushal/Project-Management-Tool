<?php

namespace App\Repositories\Skill;

use App\Models\Skill;
use App\Models\Workspace;
use App\Repositories\CrudRepository;
use Illuminate\Database\Eloquent\Collection;

class SkillRepository extends CrudRepository implements SkillRepositoryInterface
{

    public function __construct(Skill $model)
    {
        parent::__construct($model);
    }

    public function getAll(array $filters = [], array $relations = [],array $sortBy = []): Collection
    {
        $query = $this->model->query();

        if(isset($filters['workspaceId'])){
            $query->where('workspace_id', $filters['workspaceId']);
        }

        return $query->get();
    }

    public function deleteByWorkspaceId(int $workspaceId)
    {
        $this->model->where('workspace_id',$workspaceId)->delete();
    }
}
