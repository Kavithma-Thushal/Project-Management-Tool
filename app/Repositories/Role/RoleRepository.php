<?php

namespace App\Repositories\Role;

use App\Repositories\CrudRepository;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class RoleRepository extends CrudRepository implements RoleRepositoryInterface
{

    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function getAll(array $filters = [], array $relations = [], array $sortBy = []): Collection
    {
        $query = Role::query();
        if(($filters['status'])){
            $query->where('status',1);
        }
        return $query->get();
    }
}
