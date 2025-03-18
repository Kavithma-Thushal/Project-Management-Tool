<?php

namespace App\Repositories\Role;

use App\Repositories\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryInterface extends CrudRepositoryInterface
{
    public function getAll(array $filters = [], array $relations = [], array $sortBy = []): Collection;

    public function findByName(int $id, array $relations = null): ?object;
}
