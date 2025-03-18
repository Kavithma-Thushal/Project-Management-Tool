<?php

namespace App\Repositories\ProjectTaskStatus;

use App\Repositories\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface ProjectTaskStatusRepositoryInterface extends CrudRepositoryInterface
{
    public function getAll(array $filters,array $relations = [],array $sortBy = []): Collection;

    public function deleteByProjectId(int $projectId) : void;
}
