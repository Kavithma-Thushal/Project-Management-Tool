<?php

namespace App\Repositories\TaskMedia;

use App\Repositories\CrudRepositoryInterface;

interface TaskMediaRepositoryInterface extends CrudRepositoryInterface
{
    public function deleteByTaskId(int $taskId);
}
