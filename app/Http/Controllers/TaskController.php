<?php

namespace App\Http\Controllers;

use App\Classes\ErrorResponse;
use App\Http\Requests\ProjectGetByCodeRequest;
use App\Http\Requests\TaskAddRequest;
use App\Http\Requests\TaskDeleteRequest;
use App\Http\Requests\TaskStatusUpdateRequest;
use App\Http\Requests\TaskCommentRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\TaskWorkLogRequest;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskTimelineResource;
use App\Http\Resources\TaskWorkLogResource;
use App\Http\Services\TaskService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskController extends Controller
{

    public function __construct(private TaskService $taskService)
    {
    }

    public function getById(int $id)
    {
        try {
            $data = $this->taskService->getById($id);
            return new SuccessResource(['data' => new TaskResource($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function getByCode(string $code)
    {
        try {
            $data = $this->taskService->getByCode($code);
            return new SuccessResource(['data' => TaskResource::collection($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function getKanbanTasks($projectId)
    {
        try {
            $data = $this->taskService->getKanbanTasks($projectId);
            return new SuccessResource(['data' => $data]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function getKanbanTaskByCode($projectCode)
    {
        try {
            $data = $this->taskService->getKanbanTaskByCode($projectCode);
            return new SuccessResource(['data' => $data]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function add(TaskAddRequest $request)
    {
        try {
            $task = $this->taskService->add($request->validated());
            return new SuccessResource([
                'message' => 'Task created successfully',
                'data' => new TaskResource($task)
            ]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function changeStatus($id, TaskStatusUpdateRequest $request)
    {
        try {
            $this->taskService->changeStatus($id, $request->validated());
            return new SuccessResource([
                'message' => 'Task updated successfully'
            ]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function update($id, TaskUpdateRequest $request)
    {
        try {
            $this->taskService->update($id, $request->validated());
            return new SuccessResource(['data' => ['message' => 'Task updated successfully']]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function delete(TaskDeleteRequest $request)
    {
        try {
            $this->taskService->delete($request->id);
            return new SuccessResource(['message' => 'Task deleted']);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function addTaskWorkLog(TaskWorkLogRequest $request)
    {
        try {
            $this->taskService->addTaskWorkLog($request->validated());
            $taskWorkLogs = $this->taskService->getTaskWorkLogByUser(auth()->id());
            return new SuccessResource([
                'message' => 'Task Work Log created successfully',
                'data' => TaskWorklogResource::collection($taskWorkLogs)
            ]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function updateTaskWorkLog(TaskWorkLogRequest $request, $id)
    {
        try {
            $this->taskService->updateTaskWorkLog($id, $request->validated());
            $taskWorkLogs = $this->taskService->getTaskWorkLogByUser(auth()->id());
            return new SuccessResource([
                'message' => 'Task Work Log updated successfully',
                'data' => TaskWorklogResource::collection($taskWorkLogs)
            ]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function deleteTaskWorkLog($id)
    {
        try {
            $this->taskService->deleteTaskWorkLog($id);
            return new SuccessResource(['message' => 'Task Work Log deleted successfully']);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function getByIdTaskWorkLog(int $id)
    {
        try {
            $data = $this->taskService->getByIdTaskWorkLog($id);
            return new SuccessResource(['data' => new TaskWorkLogResource($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function addTaskComment(TaskCommentRequest $request)
    {
        try {
            $this->taskService->addTaskComment($request->task_id, $request->validated());
            $taskComments = $this->taskService->getTaskCommentsByUser(auth()->id());
            return new SuccessResource([
                'message' => 'Task comment created successfully',
                'data' => TaskTimelineResource::collection($taskComments)
            ]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function updateTaskComment(TaskCommentRequest $request, $id)
    {
        try {
            $this->taskService->updateTaskComment($id, $request->validated());
            $taskComments = $this->taskService->getTaskCommentsByUser(auth()->id());
            return new SuccessResource([
                'message' => 'Task Comment updated successfully',
                'data' => TaskTimelineResource::collection($taskComments)
            ]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function deleteTaskComment($id)
    {
        try {
            $this->taskService->deleteTaskComment($id);
            return new SuccessResource(['message' => 'Task Comment deleted successfully']);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function getTaskTimelineById(int $id)
    {
        try {
            $data = $this->taskService->getTaskTimelineById($id);
            return new SuccessResource(['data' => TaskTimelineResource::collection($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }
}
