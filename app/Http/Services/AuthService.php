<?php

namespace App\Http\Services;

use App\Classes\ErrorResponse;
use App\Models\PasswordHistory;
use App\Enums\HttpStatus;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface,
        private UserService $userService,
        private WorkspaceService $workspaceService
    ) {}

    public function getAuthUser(): ?Authenticatable
    {
        return Auth::user();
    }

    public function login(array $data): array
    {
        $user =  $this->userRepositoryInterface->findByEmail($data['email']);
        if (!$user) throw new HttpException(HttpStatus::UNPROCESSABLE_CONTENT, 'Username or password invalid');

        // Match user password and login
        if (!Hash::check($data['password'], $user->password))
            throw new HttpException(HttpStatus::UNPROCESSABLE_CONTENT, 'Username or password invalid');

        // Create personal access token
        $token = $user->createToken('cgl')->accessToken;
        if ($token == null)
            throw new HttpException(HttpStatus::INTERNAL_SERVER_ERROR, 'User authentication failed');
        // if user not authenticated at this point it will consider as unhandled server error
        // returning user data array
        return ['user' => $user, 'access_token' => $token];
    }

    public function register(array $data)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->add($data);
            $workspace = $this->createWorkspace($user->id);
            $user->selected_workspace_id = $workspace->id;
            $user->save();
            DB::commit();
            return $user;
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function createWorkspace($userId) {
        $workspaceData = [
            'name' => 'My Workspace',
            'created_by' => $userId,
        ];

        return $this->workspaceService->add($workspaceData);
    }


    public function changePassword(array $data)
    {
        // Retrieve the currently authenticated user
        $user = Auth::user();

        // Verify that the provided current password matches the stored password
        if (!Hash::check($data['current_password'], $user->password)) {
            throw new HttpException(HttpStatus::UNPROCESSABLE_CONTENT, 'Current password is incorrect');
        }

        // Ensure that the new password is not the same as the current password
        if (Hash::check($data['new_password'], $user->password)) {
            throw new HttpException(HttpStatus::UNPROCESSABLE_CONTENT, 'New password cannot be the same as the current password');
        }

        // Update the user's password with the new hashed password
        $user->password = Hash::make($data['new_password']);
        $user->save();
    }

}
