<?php

namespace App\Http\Services;

use App\Classes\CodeGenerator;
use App\Classes\MessageGenerator;
use App\Enums\TaskTimelineTypeEnum;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskTimeline;
use App\Models\TaskWorkLog;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\ProjectMember\ProjectMemberRepositoryInterface;
use App\Repositories\ProjectTaskStatus\ProjectTaskStatusRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Repositories\TaskTimeline\TaskTimelineRepositoryInterface;
use App\Repositories\TaskWorkLog\TaskWorkLogRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskService
{


    public function __construct(
        private TaskRepositoryInterface              $taskRepositoryInterface,
        private TaskWorkLogRepositoryInterface       $taskWorkLogRepository,
        private TaskMediaService                     $taskMediaService,
        private ProjectTaskStatusRepositoryInterface $projectTaskStatusRepositoryInterface,
        private TaskTimelineService                  $taskTimelineService,
        private TaskTimelineRepositoryInterface      $taskTimelineRepositoryInterface,
        private MessageGenerator                     $messageGenerator,
        private ProjectRepositoryInterface           $projectRepositoryInterface,
    )
    {
    }

    public function getById($id)
    {
        return $this->taskRepositoryInterface->find($id, ['taskTimelines', 'taskWorkLogs']);
    }

    public function getByCode(string $code)
    {
        $data['code'] = $code;
        return $this->taskRepositoryInterface->getAll($data);
    }

    public function getKanbanTasks($projectId)
    {
        // Fetch project task statuses

        $taskStatuses = $this->projectTaskStatusRepositoryInterface->getAll(['projectId' => $projectId]);


        $kanbanData = $taskStatuses->map(function ($status) {
            return [
                'status_id' => $status->id,
                'name' => $status->name,
                'color' => $status->color,
                'tasks' => $status->taskTimelines->map(function ($timeline) use ($status) {
                    $task = $timeline->task;
                    return [
                        'id' => $task->id,
                        'title' => $task->title ?? 'Untitled Task',
                        'date' => \Carbon\Carbon::parse($task->due_date)->format('d M, Y'),
                        'content' => $task->description ?? 'No description',
                        'progress' => 15,
                        'user' => [
                            'name' => $timeline->user->username,
                            'profile' => $timeline->user->avatar_media_id ?? 'default-profile.jpg',
                        ],
                        'timeline' => [
                            'name' => $timeline->name,
                            'date' => \Carbon\Carbon::parse($timeline->created_at)->format('d M, Y'),
                        ],
                        'status' => $task->status->name,
                        'variant' => 'danger'
                    ];
                }),
            ];
        });


        // $taskStatuses = ProjectTaskStatus::where('project_id', $projectId)
        //     ->with(['tasks.users'])
        //     ->get();

        // Format the data for the Kanban board
        // $kanbanData = $taskStatuses->map(function ($status) {
        //     return [
        //         'status' => $status->name,
        //         'color' => $status->color,
        //         'tasks' => $status->tasks->map(function ($task) {
        //             return [
        //                 'id' => '#' . $task->id,
        //                 'title' => $task->name,
        //                 'date' => \Carbon\Carbon::parse($task->due_date)->format('d M, Y'),
        //                 'content' => $task->description ?? 'No description', // Assuming description is nullable
        //                 'progress' => $task->progress ?? 0,
        //                 'users' => $task->users->map(function ($user) {
        //                     return [
        //                         'name' => $user->name,
        //                         'profile' => $user->profile_image_url ?? 'default-profile.jpg',
        //                     ];
        //                 }),
        //             ];
        //         }),
        //     ];
        // });
        Log::info($kanbanData);
        return $kanbanData;
    }

    public function getKanbanTaskByCode(string $projectCode)
    {
        $project=$this->projectRepositoryInterface->getByCode($projectCode);
        if (!$project) {
            throw new \Exception('Project not found.');
        }
        $taskStatuses = $this->projectTaskStatusRepositoryInterface->getAll(['projectId' => $project->id]);


        $kanbanData = $taskStatuses->map(function ($status) {
            return [
                'status_id' => $status->id,
                'name' => $status->name,
                'color' => $status->color,
                'tasks' => $status->taskTimelines->map(function ($timeline) use ($status) {
                    $task = $timeline->task;
                    return [
                        'id' => $task->id,
                        'title' => $task->title ?? 'Untitled Task',
                        'date' => \Carbon\Carbon::parse($task->due_date)->format('d M, Y'),
                        'content' => $task->description ?? 'No description',
                        'progress' => 15,
                        'user' => [
                            'name' => $timeline->user->username,
                            'profile' => $timeline->user->avatar_media_id ?? 'default-profile.jpg',
                        ],
                        'timeline' => [
                            'name' => $timeline->name,
                            'date' => \Carbon\Carbon::parse($timeline->created_at)->format('d M, Y'),
                        ],
                        'status' => $task->status->name,
                        'variant' => 'danger'
                    ];
                }),
            ];
        });
        return $kanbanData;
    }

    public function add(array $data): Task
    {
        DB::beginTransaction();
        try {
            $taskCode = CodeGenerator::generateWorkspaceUuid();
            $taskData = [
                'parent_task_id' => $data['parent_task_id'] ?? null,
                'project_id' => $data['project_id'],
                'task_type_id' => $data['task_type_id'],
                'assignee_id' => $data['assignee_id'] ?? null,
                'code' => $taskCode,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'due_date' => $data['due_date'] ?? null,
                'priority' => $data['priority'],
                'estimated_hours' => $data['estimated_hours'] ?? null,
                'spent_hours' => $data['spent_hours'] ?? null,
                'status_id' => $data['project_task_status_id'],
            ];
            $task = $this->taskRepositoryInterface->add($taskData);
            if (isset($data['media'])) {
                $this->taskMediaService->updateMedia($task->id, $data);
            }

            $userName = Auth::user()->username;
            $status = $this->projectTaskStatusRepositoryInterface->find($this->taskRepositoryInterface->find($task->id)->status_id)->name;
            $data['description'] = $this->messageGenerator->generateTaskCreateMessage($userName,$status);
            $this->taskTimelineService->add($task->id, $data);

            DB::commit();
            return $task;
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function changeStatus(int $id, array $data)
    {
        DB::beginTransaction();
        try {

            $userName = Auth::user()->username;
//            dd($id);
            $currentStatus = $this->projectTaskStatusRepositoryInterface->find($this->taskRepositoryInterface->find($id)->status_id)->name;
//            dd($currentStatus);
            $newStatus = $this->projectTaskStatusRepositoryInterface->find($data['status_id'])->name;
            $description = $this->messageGenerator->generateChangeStatusMessage($userName, $currentStatus, $newStatus);

            $taskData = [
                'status_id' => $data['status_id'],
            ];

            $task = $this->taskRepositoryInterface->update($id, $taskData);

            $taskTimelineData = [
                'task_id' => $id,
                'project_task_status_id' => $data['status_id'],
                'description' => $description,
                'type' => TaskTimelineTypeEnum::STATUS_CHANGE,
            ];

            $this->taskTimelineService->add($id, $taskTimelineData);
            DB::commit();
            return $task;
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // public function update(int $id,array $data)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $projectData = [
    //             'name' => $data['name'],
    //             'description' => $data['description'] ?? null,
    //         ];

    //         if (isset($data['privacy_type'])) {
    //             $projectData['privacy_type'] = $data['privacy_type'];
    //         }

    //         $project =  $this->projectRepositoryInterface->update($id,$projectData);
    //         DB::commit();
    //         return $project;
    //     } catch (HttpException $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }

    public function deleteByProject(int $projectId): void
    {
        $this->taskRepositoryInterface->deleteByProjectId($projectId);
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {

            // Update Task Data
            $taskData = [
                'parent_task_id' => $data['parent_task_id'] ?? null,
                'task_type_id' => $data['task_type_id'],
                'assignee_id' => $data['assignee_id'] ?? null,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'due_date' => $data['due_date'] ?? null,
                'priority' => $data['priority'],
                'estimated_hours' => $data['estimated_hours'] ?? null,
                'spent_hours' => $data['spent_hours'] ?? null,
                'status_id' => $data['project_task_status_id'],
            ];
            $this->taskRepositoryInterface->update($id, $taskData);

            // Add Task Work Log
            if (isset($data['work_log'])) {
                $logData = [
                    'task_id' => $id,
                    'user_id' => Auth::id(),
                    'description' => $data['work_log']['description'] ?? 'No description provided',
                    'date' => $data['work_log']['date'] ?? null,
                    'spent_time' => $data['work_log']['spent_hours'] ?? 0,
                ];
                $this->taskWorkLogRepository->add($logData);
            }

            if (isset($data['media'])) {
                $this->taskMediaService->updateMedia($id, $data);
            }

            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        DB::beginTransaction();
        try {
            // Find the task by ID
            $task = $this->taskRepositoryInterface->find($id);

            if (!$task) {
                throw new HttpException(404, 'Task not found');
            }

            // Delete related task work logs
            $this->taskWorkLogRepository->delete($id);

            // Delete task timelines
            $task->taskTimelines()->delete();

            // Delete task media
            $task->taskMedia()->delete();

            // Delete the task itself
            $task->delete();

            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function addTaskWorkLog(array $data): TaskWorkLog
    {
        DB::beginTransaction();
        try {
            $taskWorkLog = $this->taskWorkLogRepository->add([
                'task_id' => $data['task_id'],
                'user_id' => auth()->id(),
                'description' => $data['description'] ?? null,
                'date' => $data['date'],
                'spent_time' => $data['spent_time'],
            ]);

            DB::commit();
            return $taskWorkLog;
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateTaskWorkLog(int $id, array $data): TaskWorkLog
    {
        DB::beginTransaction();
        try {
            $taskWorkLog = $this->taskWorkLogRepository->find($id);

            if (!$taskWorkLog) {
                throw new HttpException(404, 'Task Work Log not found');
            }

            // Update the task work log
            $this->taskWorkLogRepository->update($id, [
                'description' => $data['description'] ?? null,
                'date' => $data['date'],
                'spent_time' => $data['spent_time'],
            ]);

            // Fetch the updated task work log
            $updatedTaskWorkLog = $this->taskWorkLogRepository->find($id);

            DB::commit();
            return $updatedTaskWorkLog;
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteTaskWorkLog(int $id): void
    {
        DB::beginTransaction();
        try {
            $taskWorkLog = $this->taskWorkLogRepository->find($id);

            if (!$taskWorkLog) {
                throw new HttpException(404, 'Task Work Log not found');
            }

            $this->taskWorkLogRepository->delete($id);

            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getByIdTaskWorkLog($id)
    {
        return $this->taskWorkLogRepository->find($id, ['task']);
    }

    public function getTaskWorkLogByUser(int $userId)
    {
        try {
            return $this->taskWorkLogRepository->getByUserId($userId);
        } catch (HttpException $e) {
            throw $e;
        }
    }

    public function addTaskComment(int $taskId, array $data): TaskTimeline
    {
        $taskCommentData = [
            'task_id' => $taskId,
            'media_id' => $data['media_id'] ?? null,
            'project_task_status_id' => $data['project_task_status_id'],
            'user_id' => Auth::user()->id,
            'type' => TaskTimelineTypeEnum::COMMENT,
            'description' => $data['description'] ?? null,
        ];
        return $this->taskTimelineRepositoryInterface->add($taskCommentData);
    }

    public function updateTaskComment(int $id, array $data): TaskTimeline
    {
        DB::beginTransaction();
        try {
            $taskComment = $this->taskTimelineRepositoryInterface->find($id);

            if (!$taskComment) {
                throw new HttpException(404, 'Task Comment not found');
            }

            // Update the task comment
            $this->taskTimelineRepositoryInterface->update($id, [
                'project_task_status_id' => $data['project_task_status_id'],
                'description' => $data['description'] ?? null,
            ]);

            // Fetch the updated task comment
            $updatedTaskComment = $this->taskTimelineRepositoryInterface->find($id);

            DB::commit();
            return $updatedTaskComment;
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteTaskComment(int $id): void
    {
        DB::beginTransaction();
        try {
            $taskComment = $this->taskTimelineRepositoryInterface->find($id);

            if (!$taskComment) {
                throw new HttpException(404, 'Task Comment not found');
            }

            $this->taskTimelineRepositoryInterface->delete($id);

            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getTaskCommentsByUser(int $userId)
    {
        try {
            return $this->taskTimelineRepositoryInterface->getByUserId($userId);
        } catch (HttpException $e) {
            throw $e;
        }
    }

    public function getTaskTimelineById($id)
    {
        return $this->taskTimelineRepositoryInterface->getAll(['task_id' => $id]);
    }
}
