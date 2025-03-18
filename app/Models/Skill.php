<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $guarded = [];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }
}
