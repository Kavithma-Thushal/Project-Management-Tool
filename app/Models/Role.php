<?php

namespace App\Models;

use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    protected $guarded = [];
    protected $guard_name = 'web';
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
