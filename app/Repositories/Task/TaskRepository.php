<?php

namespace App\Repositories\Task;
use App\Models\Task;
use App\Repositories\CrudRepository;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository extends CrudRepository implements TaskRepositoryInterface
{

    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    public function getAll(array $filters = [], array $relations = [],array $sortBy = []): Collection
    {
        $query = $this->model->query();

        if(isset($filters['projectId'])){
            $query->where('project_id', $filters['projectId']);
        }

        if(isset($filters['code'])){
            $query->where('code', $filters['code']);
        }

        return $query->get();
    }

    public function deleteByProjectId(int $projectId): void
    {
        $this->model->where('project_id',$projectId)->delete();
    }

}
