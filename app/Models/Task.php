<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function status()
    {
        return $this->belongsTo(ProjectTaskStatus::class);
    }

    public function taskTimelines()
    {
        return $this->hasMany(TaskTimeline::class);
    }

    public function taskMedia()
    {
        return $this->hasMany(TaskMedia::class);
    }

    public function taskWorkLogs()
    {
        return $this->hasMany(TaskWorkLog::class);
    }
}
