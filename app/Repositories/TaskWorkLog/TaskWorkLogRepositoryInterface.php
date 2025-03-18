<?php

namespace App\Repositories\TaskWorkLog;

use App\Repositories\CrudRepositoryInterface;

interface TaskWorkLogRepositoryInterface extends CrudRepositoryInterface
{
    public function getByUserId(int $userId);
}
