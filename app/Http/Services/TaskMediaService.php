<?php

namespace App\Http\Services;

use App\Repositories\TaskMedia\TaskMediaRepositoryInterface;
use App\Repositories\TaskStatus\TaskStatusRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskMediaService
{


    public function __construct(
        private TaskMediaRepositoryInterface $taskMediaRepositoryInterface,
    ) {}

    public function getAll(array $filters): Collection
    {
        return $this->taskMediaRepositoryInterface->getAll($filters);
    }

    public function updateMedia(int $taskId, array $data)
    {
        DB::beginTransaction();
        try {
            $this->taskMediaRepositoryInterface->deleteByTaskId($taskId);
            foreach ($data['media'] as $media) {
                $mediaData = [
                    'task_id' => $taskId,
                    'user_id' => Auth::user()->id,
                    'media_id' => $media['media_id'],
                ];

            $this->taskMediaRepositoryInterface->add($mediaData);
            }
            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteByTaskId(int $taskId): void
    {
        $this->taskMediaRepositoryInterface->deleteByTaskId($taskId);
    }
}
