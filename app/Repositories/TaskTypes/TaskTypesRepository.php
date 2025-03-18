<?php

namespace App\Repositories\TaskTypes;
use App\Models\TaskType;
use App\Repositories\CrudRepository;

class TaskTypesRepository extends CrudRepository implements TaskTypesRepositoryInterface
{

    public function __construct(TaskType $model)
    {
        parent::__construct($model);
    }
}
