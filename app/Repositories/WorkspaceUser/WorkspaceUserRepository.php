<?php

namespace App\Repositories\WorkspaceUser;
use App\Models\WorkspaceUser;
use App\Repositories\CrudRepository;
use Illuminate\Database\Eloquent\Collection;

class WorkspaceUserRepository extends CrudRepository implements WorkspaceUserRepositoryInterface
{

    public function __construct(WorkspaceUser $model)
    {
        parent::__construct($model);
    }

    public function getAll(array $filters = [], array $relations = [],array $sortBy = []): Collection
    {
        $query = $this->model->query();

        if(isset($filters['workspaceId'])){
            $query->where('workspace_id', $filters['workspaceId']);
        }

        return $query->get();
    }

}
