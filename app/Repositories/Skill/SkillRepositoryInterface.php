<?php

namespace App\Repositories\Skill;

use App\Repositories\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface SkillRepositoryInterface extends CrudRepositoryInterface
{
    public function getAll(array $filters,array $relations = [],array $sortBy = []): Collection;

    public function deleteByWorkspaceId(int $workspaceId);
}
