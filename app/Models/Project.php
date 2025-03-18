<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];


    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function projectTaskStatus()
    {
        return $this->hasMany(ProjectTaskStatus::class);
    }

    public function projectSkills()
    {
        return $this->hasMany(ProjectSkill::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
