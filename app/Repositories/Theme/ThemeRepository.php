<?php

namespace App\Repositories\Theme;

use App\Models\User;
use App\Repositories\CrudRepository;

class ThemeRepository extends CrudRepository implements ThemeRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function changeTheme($user, int $theme)
    {
        $user->update(['theme' => $theme]);
        return $user;
    }
}
