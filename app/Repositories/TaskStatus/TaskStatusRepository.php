<?php

namespace App\Repositories\TaskStatus;

use App\Models\TaskStatus;
use App\Repositories\CrudRepository;

class TaskStatusRepository extends CrudRepository implements TaskStatusRepositoryInterface
{

    public function __construct(TaskStatus $model)
    {
        parent::__construct($model);
    }

}
