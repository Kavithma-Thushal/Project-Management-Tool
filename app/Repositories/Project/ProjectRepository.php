<?php

namespace App\Repositories\Project;

use App\Models\Invitation;
use App\Models\Project;
use App\Repositories\CrudRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProjectRepository extends CrudRepository implements ProjectRepositoryInterface
{

    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    public function getAll(array $filters = [], array $relations = [],array $sortBy = []): Collection
    {
        $query = $this->model->query();

        if(isset($filters['workspaceId'])){
            $query->where('workspace_id', $filters['workspaceId']);
        }

        if(isset($filters['code'])){
            $query->where('code', $filters['code']);
        }

        return $query->get();
    }

    public function findByToken(string $token): ?Invitation
    {
        return Invitation::with('project')->where('token', $token)->first();
    }

    public function getByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }

}
