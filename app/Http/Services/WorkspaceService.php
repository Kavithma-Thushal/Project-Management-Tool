<?php

namespace App\Http\Services;

use App\Classes\CodeGenerator;
use App\Enums\WorkspaceUserStatusesEnum;
use App\Models\Workspace;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Workspace\WorkspaceRepositoryInterface;
use App\Repositories\WorkspaceUser\WorkspaceUserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WorkspaceService
{


    public function __construct(
        private WorkspaceRepositoryInterface $workspaceRepositoryInterface,
        private WorkspaceUserRepositoryInterface $workspaceUserRepositoryInterface,
        private UserRepositoryInterface $userRepositoryInterface,
        private SkillService $skillService,
        private ProjectService $projectService
    ) {}

    public function getAll(array $filters): Collection
    {
        $userId = Auth::user()->id;
        $filters['userId'] = $userId;
        return $this->workspaceRepositoryInterface->getAll($filters);
    }

    public function getById($id)
    {
        return $this->workspaceRepositoryInterface->find($id);
    }

    public function getByUser(array $filters): Collection
    {
        return $this->workspaceRepositoryInterface->getAll($filters);
    }

    public function add(array $data): Workspace
    {
        DB::beginTransaction();
        try {
            $workspaceCode = CodeGenerator::generateWorkspaceUuid();
            $workspaceData = [
                'code' => $workspaceCode,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'created_by' => $data['created_by'] ?? Auth::user()->id,
                'color' => $data['color'] ?? null,
            ];
            $workspace =  $this->workspaceRepositoryInterface->add($workspaceData);

            if (isset($data['skills'])) {
               $this->skillService->updateSkills($workspace->id,$data);
            }

            $this->createWorkspaceUser($workspace);

            DB::commit();
            return $workspace;
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function createWorkspaceUser($workspace) {

        $workspaceUserData = [
            'workspace_id' => $workspace->id,
            'user_id' => $workspace['created_by'] ?? Auth::user()->id,
            'status' => WorkspaceUserStatusesEnum::ACTIVE
        ];

        if (isset($workspace['created_by'])) {
            $workspaceUserData['is_admin'] = 1;
        }
        return $this->workspaceUserRepositoryInterface->add($workspaceUserData);
    }

    public function update(int $id,array $data)
    {
        DB::beginTransaction();
        try {
            $workspaceData = [
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'color' => $data['color'] ?? null,
            ];
            $workspace =  $this->workspaceRepositoryInterface->update($id,$workspaceData);

            if (isset($data['skills'])) {
                $this->skillService->updateSkills($id,$data);
             }
            DB::commit();
            return $workspace;
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        DB::beginTransaction();
        try {
            // Find the workspace by Id
            $workspace = $this->workspaceRepositoryInterface->find($id);

            // Delete all project-related data (tasks, skills, etc.)
            if ($workspace->workspaceProjects) {
                foreach ($workspace->workspaceProjects as $project) {
                    $this->projectService->delete($project->id);
                }
            }

            // Remove selected_workspace_id from users
            $this->userRepositoryInterface->deleteByWorkspaceId($id);

            // Delete workspace skills
            $this->skillService->deleteByWorkspaceId($id);

            // Delete workspace users
            $this->workspaceUserRepositoryInterface->delete($id);

            // Delete workspace itself
            $this->workspaceRepositoryInterface->delete($id);

            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
