<?php

namespace App\Repositories\ProjectSkill;

use App\Models\ProjectSkill;
use App\Models\Skill;
use App\Models\Workspace;
use App\Repositories\CrudRepository;
use Illuminate\Database\Eloquent\Collection;

class ProjectSkillRepository extends CrudRepository implements ProjectSkillRepositoryInterface
{

    public function __construct(ProjectSkill $model)
    {
        parent::__construct($model);
    }

    public function getAll(array $filters = [], array $relations = [],array $sortBy = []): Collection
    {
        $query = $this->model->query();

        if(isset($filters['projectId'])){
            $query->where('project_id', $filters['projectId']);
        }

        return $query->get();
    }

    public function deleteByProjectId(int $projectId): void
    {
        $this->model->where('project_id',$projectId)->delete();
    }
}
