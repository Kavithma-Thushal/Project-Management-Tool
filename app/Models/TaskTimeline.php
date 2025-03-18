<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskTimeline extends Model
{
    protected $guarded = [];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Task timeline belongs to a project task status
    public function projectTaskStatus()
    {
        return $this->belongsTo(ProjectTaskStatus::class);
    }
}
