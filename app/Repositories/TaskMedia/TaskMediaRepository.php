<?php

namespace App\Repositories\TaskMedia;

use App\Models\TaskMedia;
use App\Repositories\CrudRepository;

class TaskMediaRepository extends CrudRepository implements TaskMediaRepositoryInterface
{

    public function __construct(TaskMedia $model)
    {
        parent::__construct($model);
    }

    public function deleteByTaskId(int $taskId)
    {
        $this->model->where('task_id',$taskId)->delete();
    }

}
