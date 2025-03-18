<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    protected $guarded = [];

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function workspaceProjects()
    {
        return $this->hasMany(Project::class);
    }

    public function workspaceSkills()
    {
        return $this->hasMany(Skill::class);
    }
}
