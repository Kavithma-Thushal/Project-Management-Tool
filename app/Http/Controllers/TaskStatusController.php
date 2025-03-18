<?php

namespace App\Http\Controllers;

use App\Classes\ErrorResponse;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\TaskStatusResource;
use App\Http\Services\TaskStatusService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskStatusController extends Controller
{

    private TaskStatusService $taskStatusService;
    public function __construct(TaskStatusService $taskStatusService)
    {
        $this->taskStatusService = $taskStatusService;
    }

    public function getAll(Request $request)
    {
        try {
            $data = $this->taskStatusService->getAll($request->all());
            return new SuccessResource(['data' => TaskStatusResource::collection($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }
}
