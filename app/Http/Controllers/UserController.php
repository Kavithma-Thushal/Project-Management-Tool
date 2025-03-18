<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeThemeRequest;
use App\Classes\ErrorResponse;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function update(UserUpdateRequest $request)
    {
        try {
            $data = $this->userService->update($request->validated());
            return new SuccessResource(['data' => new UserResource($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function getById(int $id)
    {
        try {
            $data = $this->userService->getById($id);
            return new SuccessResource(['data' => new UserResource($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function getAll(Request $request)
    {
        try {
            $data = $this->userService->getAll($request->all());
            return new SuccessResource(['data' => UserResource::collection($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function changeTheme(ChangeThemeRequest $request)
    {
        try {
            $user = $this->userService->changeTheme($request->input('theme'));
            return new SuccessResource(['data' => new UserResource($user)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }
}
