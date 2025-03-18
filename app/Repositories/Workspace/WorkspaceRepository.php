<?php

namespace App\Repositories\Workspace;

use App\Models\Workspace;
use App\Repositories\CrudRepository;
use Illuminate\Database\Eloquent\Collection;

class WorkspaceRepository extends CrudRepository implements WorkspaceRepositoryInterface
{

    public function __construct(Workspace $model)
    {
        parent::__construct($model);
    }

    public function getAll(array $filters = [], array $relations = [],array $sortBy = []): Collection
    {
        $query = $this->model->query();

        if(isset($filters['userId'])){
            $query->where('created_by', $filters['userId']);
        }

        return $query->get();
    }
}
