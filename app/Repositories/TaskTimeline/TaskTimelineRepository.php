<?php

namespace App\Repositories\TaskTimeline;
use App\Models\TaskTimeline;
use App\Repositories\CrudRepository;
use Illuminate\Database\Eloquent\Collection;

class TaskTimelineRepository extends CrudRepository implements TaskTimelineRepositoryInterface
{

    public function __construct(TaskTimeline $model)
    {
        parent::__construct($model);
    }

    public function getByUserId(int $userId)
    {
        return TaskTimeline::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    public function getAll(array $filters = [], array $relations = [],array $sortBy = []): Collection
    {
        $query = $this->model->query();

        if(isset($filters['task_id'])){
            $query->where('task_id', $filters['task_id']);
        }

        return $query->get();
    }
}
