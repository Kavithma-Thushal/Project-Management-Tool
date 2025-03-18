<?php

namespace App\Http\Services;

use App\Enums\TaskTimelineTypeEnum;
use App\Models\ProjectSkill;
use App\Models\Skill;
use App\Models\TaskTimeline;
use App\Repositories\ProjectSkill\ProjectSkillRepositoryInterface;
use App\Repositories\TaskTimeline\TaskTimelineRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskTimelineService
{


    public function __construct(
        private TaskTimelineRepositoryInterface $taskTimelineRepositoryInterface,
    ) {}


    public function add(int $taskId, array $data): TaskTimeline
    {

        $timelineData = [
            'task_id' => $taskId,
            'media_id' => $data['media_id'] ?? null,
            'project_task_status_id' => $data['project_task_status_id'],
            'user_id' => Auth::user()->id,
            'type' => TaskTimelineTypeEnum::STATUS_CHANGE,
            'description' => $data['description'] ?? null,
        ];
        Log::info($timelineData);
        return $this->taskTimelineRepositoryInterface->add($timelineData);
    }


    // public function update(int $id, array $data)
    // {

    //     $skillData = [
    //         'project_id' => $data['project_id'],
    //         'skill_id' => $data['skill_id'],
    //     ];

    //     return $this->projectSkillRepositoryInterface->update($id, $skillData);
    // }


}
