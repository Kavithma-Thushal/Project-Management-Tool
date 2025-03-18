<?php

namespace App\Http\Services;

use App\Models\ProjectTaskStatus;
use App\Repositories\ProjectTaskStatus\ProjectTaskStatusRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ProjectTaskStatusService
{


    public function __construct(
        private ProjectTaskStatusRepositoryInterface $projectTaskStatusRepositoryInterface,
    ) {}

    public function add(array $data): void
    {
        $this->projectTaskStatusRepositoryInterface->add([
            'project_id' => $data['project_id'],
            'name' => $data['name'],
            'color' => $data['color'] ?? null,
        ]);
    }

    public function update(int $projectId, array $projectTaskStatuses = []):void
    {

        foreach ($projectTaskStatuses as $status) {
            $this->projectTaskStatusRepositoryInterface->updateOrCreate([
                'project_id' => $projectId,
                'name' => $status['name'],
                'color' => $status['color'] ?? null,
            ], []);
        }

    }

    public function updateProjectStatus($id, array $data): void
    {
        $this->projectTaskStatusRepositoryInterface->update($id, $data);
    }

    public function deleteByProject(int $projectId): void
    {
        $this->projectTaskStatusRepositoryInterface->deleteByProjectId($projectId);
    }

    public function find(int $id): ?ProjectTaskStatus
    {
        return $this->projectTaskStatusRepositoryInterface->find($id);
    }

    public function delete(int $id): void
    {
        $this->projectTaskStatusRepositoryInterface->delete($id);
    }
}
