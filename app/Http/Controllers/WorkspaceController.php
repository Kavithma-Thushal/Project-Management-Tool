<?php

namespace App\Http\Controllers;

use App\Classes\ErrorResponse;
use App\Http\Requests\WorkspaceAddRequest;
use App\Http\Requests\WorkspaceDeleteRequest;
use App\Http\Requests\WorkspaceUpdateRequest;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\WorkspaceGetAllResource;
use App\Http\Resources\WorkspaceResource;
use App\Http\Services\WorkspaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WorkspaceController extends Controller
{
    private WorkspaceService $workspaceService;

    public function __construct(WorkspaceService $workspaceService)
    {
        $this->workspaceService = $workspaceService;
    }

    public function getAll(Request $request)
    {
        try {
            $data = $this->workspaceService->getAll($request->all());
            return new SuccessResource(['data' => WorkspaceGetAllResource::collection($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function getById(int $id)
    {
        try {
            $data = $this->workspaceService->getById($id);
            return new SuccessResource(['data' => new WorkspaceResource($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function getByUser(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            $data = $this->workspaceService->getByUser($userId);
            return new SuccessResource(['data' => WorkspaceResource::collection($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function add(WorkspaceAddRequest $request)
    {
        try {
            $workspace = $this->workspaceService->add($request->validated());
            return new SuccessResource([
                'message' => 'Workspace created',
                'data' => new WorkspaceResource($workspace)
            ]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function update($id,WorkspaceUpdateRequest $request)
    {
        try {
            $this->workspaceService->update($id,$request->validated());
            return new SuccessResource(['data' => ['message' => 'Workspace updated successfully']]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function delete(WorkspaceDeleteRequest $request)
    {
        try {
            $this->workspaceService->delete($request->id);
            return new SuccessResource(['message' => 'Workspace deleted']);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }
}
