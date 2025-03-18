<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectTaskStatusRequest;
use App\Models\User;
use Carbon\Carbon;
use App\Classes\ErrorResponse;
use App\Enums\InvitationStatusEnum;
use App\Http\Requests\AddMemberRequest;
use App\Http\Requests\ProjectAddRequest;
use App\Http\Requests\ProjectDeleteRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\AddMemberResource;
use App\Http\Resources\ProjectGetAllResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\SuccessResource;
use App\Http\Services\ProjectService;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectController extends Controller
{
    public function __construct(private ProjectService $projectService)
    {
    }

    public function getById(int $id)
    {
        try {
            $data = $this->projectService->getById($id);
            return new SuccessResource(['data' => new ProjectResource($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function getByWorkspaceId($workspaceId)
    {
        try {
            $data = $this->projectService->getByPackageId($workspaceId);
            return new SuccessResource(['data' => ProjectGetAllResource::collection($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function getByCode(string $code)
    {
        try {
            $data = $this->projectService->getByCode($code);
            return new SuccessResource(['data' => new ProjectResource($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function add(ProjectAddRequest $request)
    {
        try {
            $project = $this->projectService->add($request->validated());
            return new SuccessResource([
                'message' => 'Project created successfully',
                'data' => new ProjectResource($project)
            ]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function update($id, ProjectUpdateRequest $request)
    {
        try {
            $this->projectService->update($id, $request->validated());
            return new SuccessResource(['data' => ['message' => 'Project updated successfully']]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function delete(ProjectDeleteRequest $request)
    {
        try {
            $this->projectService->delete($request->id);
            return new SuccessResource(['message' => 'Project deleted']);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function addMember(AddMemberRequest $request)
    {
        try {
            $invitation = $this->projectService->addMember($request->validated());
            return new SuccessResource(['message' => 'Invitation sent successfully']);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function acceptInvitation(Request $request)
    {
        try {
            $response = $this->projectService->acceptInvitation($request->query('token'));
            return new SuccessResource($response);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function addProjectStatus(ProjectTaskStatusRequest $request)
    {
        try {
            $this->projectService->addProjectStatus($request->validated());
            return new SuccessResource([
                'message' => 'Project task status created successfully'
            ]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function updateProjectStatus($id, ProjectTaskStatusRequest $request)
    {
        try {
            $this->projectService->updateProjectStatus($id, $request->validated());
            return new SuccessResource(['message' => 'Project task status updated successfully']);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function deleteProjectStatus($id)
    {
        try {
            $this->projectService->deleteProjectStatus($id);
            return new SuccessResource(['message' => 'Project task status deleted successfully']);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }
}
