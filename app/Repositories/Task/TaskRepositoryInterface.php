<?php

namespace App\Repositories\Task;

use App\Repositories\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface extends CrudRepositoryInterface
{
    public function getAll(array $filters,array $relations = [],array $sortBy = []): Collection;

    public function deleteByProjectId(int $projectId) : void;
}
