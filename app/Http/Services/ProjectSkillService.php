<?php

namespace App\Http\Services;

use App\Models\ProjectSkill;
use App\Models\Skill;
use App\Repositories\ProjectSkill\ProjectSkillRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectSkillService
{


    public function __construct(
        private ProjectSkillRepositoryInterface $projectSkillRepositoryInterface,
    ) {}


    public function getByProjectId(int $projectId)
    {
        $data['projectId'] = $projectId;
        return $this->projectSkillRepositoryInterface->getAll($data);
    }


    public function add(int $projectId, array $data): ProjectSkill
    {
        $skillData = [
            'project_id' => $projectId,
            'skill_id' => $data['skill_id'],
        ];

        return $this->projectSkillRepositoryInterface->add($skillData);
    }

    public function updateSkills(int $projectId, array $data)
    {
        DB::beginTransaction();
        try {
            $this->deleteByProject($projectId);
            foreach ($data as $skill) {
                $skillData = [
                    'project_id' => $projectId,
                    'skill_id' => $skill['skill_id'],
                ];

            $this->projectSkillRepositoryInterface->add($skillData);
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
            'project_id' => $data['project_id'],
            'skill_id' => $data['skill_id'],
        ];

        return $this->projectSkillRepositoryInterface->update($id, $skillData);
    }

    public function deleteByProject(int $projectId): void
    {
        $this->projectSkillRepositoryInterface->deleteByProjectId($projectId);
    }
}
