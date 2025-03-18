<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTaskStatus extends Model
{
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // A project task status has many timelines
    public function taskTimelines()
    {
        return $this->hasMany(TaskTimeline::class);
    }
}
