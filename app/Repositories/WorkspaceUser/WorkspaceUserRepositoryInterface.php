<?php

namespace App\Repositories\WorkspaceUser;

use App\Repositories\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface WorkspaceUserRepositoryInterface extends CrudRepositoryInterface
{
    public function getAll(array $filters,array $relations = [],array $sortBy = []): Collection;
}
