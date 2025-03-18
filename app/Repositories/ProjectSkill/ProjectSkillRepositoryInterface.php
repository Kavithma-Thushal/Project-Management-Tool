<?php

namespace App\Repositories\ProjectSkill;

use App\Repositories\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface ProjectSkillRepositoryInterface extends CrudRepositoryInterface
{
    public function getAll(array $filters,array $relations = [],array $sortBy = []): Collection;

    public function deleteByProjectId(int $projectId) : void;
}
