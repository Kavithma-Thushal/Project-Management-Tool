<?php

namespace App\Http\Services;

use App\Enums\ThemeEnum;
use App\Models\User;
use App\Repositories\Theme\ThemeRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{
    public function __construct(
        private UserRepositoryInterface  $userRepositoryInterface,
        private ThemeRepositoryInterface $themeRepository
    )
    {
    }


    public function add(array $data): User
    {
        $userData = [
            'username' => str_replace(' ', '', $data['username']),
            'email' => $data['email'],
            'password' => Hash::make(str_replace(' ', '', $data['password'])),
            'avatar_media_id' => $data['media_id'] ?? null,
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
        ];
        $user = $this->userRepositoryInterface->add($userData);
        $user->assignRole('Workspace Admin');

        return $user;
    }

    public function update(array $data)
    {
        DB::beginTransaction();
        try {
            // Get the current authenticated user
            $user = auth()->user();

            // Update the user's data
            $user->update($data);

            DB::commit();
            return $user;
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getById($id)
    {
        return $this->userRepositoryInterface->find($id);
    }

    public function getAll(array $filters): Collection
    {
        return $this->userRepositoryInterface->getAll($filters);
    }

    public function changeTheme(int $theme)
    {
        $user = auth()->user();
        return $this->themeRepository->changeTheme($user, $theme);
    }
}
