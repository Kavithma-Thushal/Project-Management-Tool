<?php

namespace App\Repositories\Theme;

use App\Repositories\CrudRepositoryInterface;

interface ThemeRepositoryInterface extends CrudRepositoryInterface
{
    public function changeTheme($user, int $theme);
}
