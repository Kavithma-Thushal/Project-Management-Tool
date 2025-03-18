<?php

namespace App\Repositories\ProjectTaskStatus;

use App\Models\ProjectTaskStatus;
use App\Repositories\CrudRepository;
use Illuminate\Database\Eloquent\Collection;

class ProjectTaskStatusRepository extends CrudRepository implements ProjectTaskStatusRepositoryInterface
{

    public function __construct(ProjectTaskStatus $model)
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
