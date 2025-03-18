<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::group(['middleware' => ['auth:api']], function () {

        Route::prefix('workspaces')->group(function () {
            Route::post('', [WorkspaceController::class, 'add'])->middleware(['permissions:workspace-add']);
            Route::get('', [WorkspaceController::class, 'getAll'])->middleware(['permissions:workspace-view-all']);
            Route::get('{id}', [WorkspaceController::class, 'getById'])->middleware(['permissions:workspace-view']);
            Route::patch('{id}', [WorkspaceController::class, 'update'])->middleware(['permissions:workspace-update']);
            Route::delete('{id}', [WorkspaceController::class, 'delete'])->middleware(['permissions:workspace-delete']);
        });

        Route::prefix('projects')->group(function () {
            Route::post('', [ProjectController::class, 'add'])->middleware(['permissions:project-add']);
            Route::get('{id}', [ProjectController::class, 'getById'])->middleware(['permissions:project-view']);
            Route::get('by-workspace/{workspaceId}', [ProjectController::class, 'getByWorkspaceId'])->middleware(['permissions:project-view-all']);
            Route::get('by-code/{code}', [ProjectController::class, 'getByCode'])->middleware(['permissions:project-get-by-code']);
            Route::patch('{id}', [ProjectController::class, 'update'])->middleware(['permissions:project-update']);
            Route::delete('{id}', [ProjectController::class, 'delete'])->middleware(['permissions:project-delete']);
            Route::post('add-member', [ProjectController::class, 'addMember'])->middleware(['permissions:project-add-member']);
            Route::get('invitation/accept', [ProjectController::class, 'acceptInvitation'])->middleware(['permissions:project-accept-invitation']);

            Route::prefix('project-status')->group(function () {
                Route::post('', [ProjectController::class, 'addProjectStatus'])->middleware(['permissions:project-status-add']);
                Route::patch('{id}', [ProjectController::class, 'updateProjectStatus'])->middleware(['permissions:project-status-update']);
                Route::delete('{id}', [ProjectController::class, 'deleteProjectStatus'])->middleware(['permissions:project-status-delete']);
            });
        });

        Route::prefix('task-status')->group(function () {
            Route::get('', [TaskStatusController::class, 'getAll'])->middleware(['permissions:task-status-view-all']);
        });

        Route::prefix('tasks')->group(function () {
            Route::post('', [TaskController::class, 'add'])->middleware(['permissions:task-add']);
            Route::get('{id}', [TaskController::class, 'getById'])->middleware(['permissions:task-view']);
            Route::get('kanban/{projectId}', [TaskController::class, 'getKanbanTasks'])->middleware(['permissions:task-view-all']);
            Route::get('kanban/by-code/{code}', [TaskController::class, 'getKanbanTaskByCode'])->middleware(['permissions:kanban-task-get-by-code']);
            Route::get('by-code/{code}', [TaskController::class, 'getByCode'])->middleware(['permissions:task-get-by-code']);
            Route::patch('change-status/{id}', [TaskController::class, 'changeStatus'])->middleware(['permissions:task-update']);
            Route::patch('{id}', [TaskController::class, 'update'])->middleware(['permissions:task-update']);
            Route::delete('{id}', [TaskController::class, 'delete'])->middleware(['permissions:task-delete']);

            Route::prefix('task-work-log')->group(function () {
                Route::post('', [TaskController::class, 'addTaskWorkLog'])->middleware(['permissions:task-work-log-add']);
                Route::patch('{id}', [TaskController::class, 'updateTaskWorkLog'])->middleware(['permissions:task-work-log-update']);
                Route::delete('{id}', [TaskController::class, 'deleteTaskWorkLog'])->middleware(['permissions:task-work-log-delete']);
                Route::get('{id}', [TaskController::class, 'getByIdTaskWorkLog'])->middleware(['permissions:task-work-log-view']);
            });

            Route::prefix('task-comments')->group(function () {
                Route::post('', [TaskController::class, 'addTaskComment'])->middleware(['permissions:task-comment-add']);
                Route::patch('{id}', [TaskController::class, 'updateTaskComment'])->middleware(['permissions:task-comment-update']);
                Route::delete('{id}', [TaskController::class, 'deleteTaskComment'])->middleware(['permissions:task-comment-delete']);
            });

            Route::prefix('task-timeline')->group(function () {
                Route::get('{id}', [TaskController::class, 'getTaskTimelineById'])->middleware(['permissions:task-timeline-view']);
            });
        });

        Route::prefix('media')->group(function () {
            Route::post('upload', [MediaController::class, 'upload'])->middleware(['permissions:media-upload']);
            Route::post('upload-multiple', [MediaController::class, 'uploadMultiple'])->middleware(['permissions:media-upload']);
        });

        Route::prefix('master-data')->group(function () {
            Route::get('/task-types', [MasterDataController::class, 'getTaskTypes'])->middleware(['permissions:master-data-task-type-view']);
            Route::get('/priorities', [MasterDataController::class, 'getPriorityTypes'])->middleware(['permissions:master-data-priority-type-view']);
        });

        Route::prefix('user')->group(function () {
            Route::patch('/update', [UserController::class, 'update']);
            Route::get('{id}', [UserController::class, 'getById']);
            Route::get('', [UserController::class, 'getAll']);
            Route::patch('/theme', [UserController::class, 'changeTheme']);
        });

        Route::patch('/change-password', [AuthController::class, 'changePassword']);
    });
});
