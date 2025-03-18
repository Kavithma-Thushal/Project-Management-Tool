<?php

namespace App\Http\Services;

use App\Classes\CodeGenerator;
use App\Enums\WorkspaceUserStatusesEnum;
use App\Models\Workspace;
use App\Repositories\TaskStatus\TaskStatusRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Workspace\WorkspaceRepositoryInterface;
use App\Repositories\WorkspaceUser\WorkspaceUserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskStatusService
{


    public function __construct(
        private TaskStatusRepositoryInterface $taskStatusRepositoryInterface,
    ) {}

    public function getAll(array $filters): Collection
    {
        return $this->taskStatusRepositoryInterface->getAll($filters);
    }

}
