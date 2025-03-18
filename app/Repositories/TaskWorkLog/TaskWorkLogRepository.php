<?php

namespace App\Repositories\TaskWorkLog;

use App\Models\TaskWorkLog;
use App\Repositories\CrudRepository;

class TaskWorkLogRepository extends CrudRepository implements TaskWorkLogRepositoryInterface
{
    public function __construct(TaskWorkLog $model)
    {
        parent::__construct($model);
    }

    public function getByUserId(int $userId)
    {
        return TaskWorkLog::where('user_id', $userId)->get();
    }
}
