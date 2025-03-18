<?php

namespace App\Providers;

use App\Repositories\Media\MediaRepository;
use App\Repositories\Media\MediaRepositoryInterface;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\ProjectMember\ProjectMemberRepository;
use App\Repositories\ProjectMember\ProjectMemberRepositoryInterface;
use App\Repositories\ProjectSkill\ProjectSkillRepository;
use App\Repositories\ProjectSkill\ProjectSkillRepositoryInterface;
use App\Repositories\ProjectTaskStatus\ProjectTaskStatusRepository;
use App\Repositories\ProjectTaskStatus\ProjectTaskStatusRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Skill\SkillRepository;
use App\Repositories\Skill\SkillRepositoryInterface;
use App\Repositories\Task\TaskRepository;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Repositories\TaskMedia\TaskMediaRepository;
use App\Repositories\TaskMedia\TaskMediaRepositoryInterface;
use App\Repositories\TaskStatus\TaskStatusRepository;
use App\Repositories\TaskStatus\TaskStatusRepositoryInterface;
use App\Repositories\TaskTimeline\TaskTimelineRepository;
use App\Repositories\TaskTimeline\TaskTimelineRepositoryInterface;
use App\Repositories\TaskTypes\TaskTypesRepository;
use App\Repositories\TaskTypes\TaskTypesRepositoryInterface;
use App\Repositories\TaskWorkLog\TaskWorkLogRepository;
use App\Repositories\TaskWorkLog\TaskWorkLogRepositoryInterface;
use App\Repositories\Theme\ThemeRepository;
use App\Repositories\Theme\ThemeRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Workspace\WorkspaceRepository;
use App\Repositories\Workspace\WorkspaceRepositoryInterface;
use App\Repositories\WorkspaceUser\WorkspaceUserRepository;
use App\Repositories\WorkspaceUser\WorkspaceUserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(MediaRepositoryInterface::class, MediaRepository::class);
        $this->app->bind(WorkspaceRepositoryInterface::class, WorkspaceRepository::class);
        $this->app->bind(WorkspaceUserRepositoryInterface::class, WorkspaceUserRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(ProjectMemberRepositoryInterface::class, ProjectMemberRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(TaskStatusRepositoryInterface::class, TaskStatusRepository::class);
        $this->app->bind(TaskTypesRepositoryInterface::class, TaskTypesRepository::class);
        $this->app->bind(TaskWorkLogRepositoryInterface::class, TaskWorkLogRepository::class);
        $this->app->bind(TaskMediaRepositoryInterface::class, TaskMediaRepository::class);
        $this->app->bind(SkillRepositoryInterface::class, SkillRepository::class);
        $this->app->bind(ProjectSkillRepositoryInterface::class, ProjectSkillRepository::class);
        $this->app->bind(ProjectTaskStatusRepositoryInterface::class, ProjectTaskStatusRepository::class);
        $this->app->bind(TaskTimelineRepositoryInterface::class, TaskTimelineRepository::class);
        $this->app->bind(ThemeRepositoryInterface::class, ThemeRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
