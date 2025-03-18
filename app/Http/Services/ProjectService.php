<?php

namespace App\Http\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Classes\CodeGenerator;
use App\Enums\InvitationStatusEnum;
use App\Mail\InvitationMail;
use App\Models\Invitation;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\User;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\ProjectMember\ProjectMemberRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectService
{


    public function __construct(
        private ProjectRepositoryInterface       $projectRepositoryInterface,
        private ProjectMemberRepositoryInterface $projectMemberRepositoryInterface,
        private ProjectSkillService              $projectSkillService,
        private TaskTimelineService              $taskTimelineService,
        private TaskService                      $taskService,
        private ProjectTaskStatusService         $projectTaskStatusService,
        private UserRepositoryInterface          $userRepository,
    )
    {
    }

    public function getById($id)
    {
        return $this->projectRepositoryInterface->find($id);
    }

    public function getByPackageId(int $packageId)
    {
        $data['workspaceId'] = $packageId;
        return $this->projectRepositoryInterface->getAll($data);
    }

    public function getByCode(string $code)
    {
//        $data['code'] = $code;
        return $this->projectRepositoryInterface->getByCode($code);
    }

    public function add(array $data): Project
    {
        DB::beginTransaction();
        try {
            $projectCode = CodeGenerator::generateWorkspaceUuid();
            $projectData = [
                'workspace_id' => $data['workspace_id'],
                'code' => $projectCode,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'privacy_type' => $data['privacy_type'],
            ];
            $project = $this->projectRepositoryInterface->add($projectData);

            if (isset($data['skills'])) {
                $this->projectSkillService->updateSkills($project->id, $data['skills']);
            }


            if (isset($data['task_statuses'])) {
                $this->projectTaskStatusService->update($project->id, $data['task_statuses']);
            }


            $project['user_id'] = Auth::user()->id;
            $this->createProjectUser($project);

            DB::commit();
            return $project;
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function createProjectUser($project)
    {

        $projectMemberData = [
            'project_id' => $project->id,
            'user_id' => $project['user_id']
        ];

        return $this->projectMemberRepositoryInterface->add($projectMemberData);
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $projectData = [
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ];

            if (isset($data['privacy_type'])) {
                $projectData['privacy_type'] = $data['privacy_type'];
            }

            if (isset($data['skills'])) {
                $this->projectSkillService->updateSkills($id, $data['skills']);
            }

            if (isset($data['task_statuses'])) {
                $this->projectTaskStatusService->update($id, $data['task_statuses']);
            }

            $project = $this->projectRepositoryInterface->update($id, $projectData);
            DB::commit();
            return $project;
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        DB::beginTransaction();
        try {

            $project = $this->projectRepositoryInterface->find($id);

            if ($project->tasks) {
                foreach ($project->tasks as $task) {
                    // Delete task timelines
                    $task->taskTimelines()->delete();

                    // Delete task media
                    $task->taskMedia()->delete();

                    // Delete the task itself
                    $task->delete();
                }
            }

            $this->projectTaskStatusService->deleteByProject($id);
            $this->projectSkillService->deleteByProject($id);
            $this->projectMemberRepositoryInterface->deleteByProjectId($id);
            $this->projectRepositoryInterface->delete($id);
            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function addMember(array $data)
    {
        DB::beginTransaction();
        try {

            // Check if the user already exists in the system
            $user = $this->userRepository->findByEmail($data['email']);
            if ($user) {
                $memberData=[
                    'project_id'=>$data['project_id'],
                    'user_id'=>$user->id,
                ];
                $this->projectMemberRepositoryInterface->add($memberData);
            }else{
                // Generate unique token
                $token = Str::random(32);
                $expiresAt = Carbon::now()->addHour(24);

                // Create invitation
                $invitation = Invitation::create([
                    'project_id' => $data['project_id'],
                    'role_id' => $data['role_id'],
                    'email' => $data['email'],
                    'status' => InvitationStatusEnum::PENDING,
                    'token' => $token,
                    'expires_at' => $expiresAt,
                ]);

                $url = config('app.frontend_url') . "/accept-invitation?token={$token}";
                Mail::to($data['email'])->send(new InvitationMail($invitation, $url));
            }
            DB::commit();
//            return $invitation;
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function acceptInvitation(string $token): array
    {
        $invitation = $this->projectRepositoryInterface->findByToken($token);

        if (!$invitation || Carbon::now()->greaterThan($invitation->expires_at)) {
            return ['is_valid' => false];
        }

        return [
            'is_valid' => true,
            'project_code' => $invitation->project->code ?? null,
            'project_id' => $invitation->project_id,
            'role_id' => $invitation->role_id,
            'email' => $invitation->email,
        ];
    }

    public function addProjectStatus(array $data)
    {
        DB::beginTransaction();
        try {
            $this->projectTaskStatusService->add([
                'project_id' => $data['project_id'],
                'name' => $data['name'],
                'color' => $data['color'] ?? null,
            ]);
            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function updateProjectStatus($id, array $data)
    {
        DB::beginTransaction();
        try {
            $this->projectTaskStatusService->updateProjectStatus($id, [
                'project_id' => $data['project_id'],
                'name' => $data['name'],
                'color' => $data['color'] ?? null,
            ]);
            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteProjectStatus(int $id): void
    {
        DB::beginTransaction();
        try {

            // Find the project task status by ID
            $taskStatus = $this->projectTaskStatusService->find($id);

            if (!$taskStatus) {
                throw new HttpException(404, "Project Task Status not found");
            }

            // Get the associated project
            $project = $this->projectRepositoryInterface->find($taskStatus->project_id);

            // Delete related task timelines, media, and the task itself
            if ($project) {
                $tasks = $project->tasks()->where('status_id', $id)->get();
                foreach ($tasks as $task) {
                    $task->taskTimelines()->delete();
                    $task->taskMedia()->delete();
                    $task->delete();
                }

                // Delete the project task status
                $this->projectTaskStatusService->delete($id);
            }

            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
