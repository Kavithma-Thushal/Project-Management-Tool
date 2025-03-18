<?php

namespace App\Repositories\TaskTimeline;

use App\Repositories\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface TaskTimelineRepositoryInterface extends CrudRepositoryInterface
{
    public function getByUserId(int $userId);

    public function getAll(array $filters,array $relations = [],array $sortBy = []): Collection;
}
