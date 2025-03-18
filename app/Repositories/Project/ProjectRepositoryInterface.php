<?php

namespace App\Repositories\Project;

use App\Models\Invitation;
use App\Repositories\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface extends CrudRepositoryInterface
{
    public function getAll(array $filters,array $relations = [],array $sortBy = []): Collection;

    public function findByToken(string $token): ?Invitation;

    public function getByCode(string $code);
}
