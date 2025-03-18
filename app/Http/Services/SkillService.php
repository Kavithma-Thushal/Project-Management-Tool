<?php

namespace App\Http\Services;

use App\Models\Skill;
use App\Repositories\Skill\SkillRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SkillService
{


    public function __construct(
        private SkillRepositoryInterface $skillRepositoryInterface,
    ) {}


    public function getByWorkspaceId(int $workspaceId)
    {
        $data['workspaceId'] = $workspaceId;
        return $this->skillRepositoryInterface->getAll($data);
    }


    public function add(int $workspaceId, array $data): Skill
    {
        $skillData = [
            'workspace_id' => $workspaceId,
            'skill' => $data['skill'],
        ];

        return $this->skillRepositoryInterface->add($skillData);
    }

    public function updateSkills(int $workspaceId, array $data)
    {
        DB::beginTransaction();
        try {
            $this->skillRepositoryInterface->deleteByWorkspaceId($workspaceId);
            foreach ($data['skills'] as $skill) {
                $skillData = [
                    'workspace_id' => $workspaceId,
                    'skill' => $skill['skill'],
                ];

            $this->skillRepositoryInterface->add($skillData);
            }
            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {

        $skillData = [
            'workspace_id' => $data['workspace_id'],
            'skill' => $data['skill'],
        ];

        return $this->skillRepositoryInterface->update($id, $skillData);
    }

    public function deleteByWorkspaceId(int $workspaceId): void
    {
        $this->skillRepositoryInterface->deleteByWorkspaceId($workspaceId);
    }
}
