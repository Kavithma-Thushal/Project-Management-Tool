<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskMedia extends Model
{
    protected $guarded = [];

    public function media(){
        return $this->belongsTo(Media::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function task(){
        return $this->belongsTo(Task::class);
    }
}
