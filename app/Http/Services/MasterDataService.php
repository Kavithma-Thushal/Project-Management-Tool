<?php

namespace App\Http\Services;

use App\Enums\PriorityTypeEnum;
use App\Repositories\TaskTypes\TaskTypesRepositoryInterface;

class MasterDataService
{

    public function __construct(
        private TaskTypesRepositoryInterface $taskStatusRepositoryInterface,
    )
    {
    }

    public function getTaskTypes()
    {
        return $this->taskStatusRepositoryInterface->getAll();
    }

    public function getPriorityTypes()
    {
        return PriorityTypeEnum::getAll();
    }
}
