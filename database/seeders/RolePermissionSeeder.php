<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::truncate();

        $guard = 'web';

        // workspaces
        $workspaceCreate = Permission::updateOrCreate(['name' => 'workspace-add', 'guard_name' => $guard, 'display_name' => 'Add Workspace']);
        $workspaceUpdate = Permission::updateOrCreate(['name' => 'workspace-update', 'guard_name' => $guard, 'display_name' => 'Update Workspace']);
        $workspaceDelete = Permission::updateOrCreate(['name' => 'workspace-delete', 'guard_name' => $guard, 'display_name' => 'Delete Workspace']);
        $workspaceView = Permission::updateOrCreate(['name' => 'workspace-view', 'guard_name' => $guard, 'display_name' => 'View Workspace']);
        $workspaceViewAll = Permission::updateOrCreate(['name' => 'workspace-view-all', 'guard_name' => $guard, 'display_name' => 'View All Workspaces']);
        $workspacePermissions = [$workspaceCreate, $workspaceUpdate, $workspaceDelete, $workspaceView, $workspaceViewAll];

        // projects
        $projectCreate = Permission::updateOrCreate(['name' => 'project-add', 'guard_name' => $guard, 'display_name' => 'Add Project']);
        $projectUpdate = Permission::updateOrCreate(['name' => 'project-update', 'guard_name' => $guard, 'display_name' => 'Update Project']);
        $projectDelete = Permission::updateOrCreate(['name' => 'project-delete', 'guard_name' => $guard, 'display_name' => 'Delete Project']);
        $projectView = Permission::updateOrCreate(['name' => 'project-view', 'guard_name' => $guard, 'display_name' => 'View Project']);
        $projectViewAll = Permission::updateOrCreate(['name' => 'project-view-all', 'guard_name' => $guard, 'display_name' => 'View All Projects']);
        $projectGetByCode = Permission::updateOrCreate(['name' => 'project-get-by-code', 'guard_name' => $guard, 'display_name' => 'View Projects By Code']);
        $projectAddMember = Permission::updateOrCreate(['name' => 'project-add-member', 'guard_name' => $guard, 'display_name' => 'Add Member']);
        $projectAcceptInvitation = Permission::updateOrCreate(['name' => 'project-accept-invitation', 'guard_name' => $guard, 'display_name' => 'Accept Invitation']);
        $projectStatusCreate = Permission::updateOrCreate(['name' => 'project-status-add', 'guard_name' => $guard, 'display_name' => 'Add Project Status']);
        $projectStatusUpdate = Permission::updateOrCreate(['name' => 'project-status-update', 'guard_name' => $guard, 'display_name' => 'Update Project Status']);
        $projectStatusDelete = Permission::updateOrCreate(['name' => 'project-status-delete', 'guard_name' => $guard, 'display_name' => 'Delete Project Status']);
        $projectPermissions = [$projectCreate, $projectUpdate, $projectDelete, $projectView, $projectViewAll, $projectAddMember, $projectGetByCode, $projectAcceptInvitation, $projectStatusCreate, $projectStatusUpdate, $projectStatusDelete];

        // tasks status
        $taskStatusCreate = Permission::updateOrCreate(['name' => 'task-status-add', 'guard_name' => $guard, 'display_name' => 'Add Task Status']);
        $taskStatusUpdate = Permission::updateOrCreate(['name' => 'task-status-update', 'guard_name' => $guard, 'display_name' => 'Update Task Status']);
        $taskStatusDelete = Permission::updateOrCreate(['name' => 'task-status-delete', 'guard_name' => $guard, 'display_name' => 'Delete Task Status']);
        $taskStatusView = Permission::updateOrCreate(['name' => 'task-status-view', 'guard_name' => $guard, 'display_name' => 'View Task Status']);
        $taskStatusViewAll = Permission::updateOrCreate(['name' => 'task-status-view-all', 'guard_name' => $guard, 'display_name' => 'View All Task Status']);
        $taskTypesViewAll = Permission::updateOrCreate(['name' => 'master-data-task-type-view', 'guard_name' => $guard, 'display_name' => 'View All Task Types']);
        $priorityTypesViewAll = Permission::updateOrCreate(['name' => 'master-data-priority-type-view', 'guard_name' => $guard, 'display_name' => 'View All Priority Types']);
        $taskStatusPermissions = [$taskStatusCreate, $taskStatusUpdate, $taskStatusDelete, $taskStatusView, $taskStatusViewAll, $taskTypesViewAll, $priorityTypesViewAll];

        // tasks
        $taskCreate = Permission::updateOrCreate(['name' => 'task-add', 'guard_name' => $guard, 'display_name' => 'Add Task']);
        $taskUpdate = Permission::updateOrCreate(['name' => 'task-update', 'guard_name' => $guard, 'display_name' => 'Update Task']);
        $taskDelete = Permission::updateOrCreate(['name' => 'task-delete', 'guard_name' => $guard, 'display_name' => 'Delete Task']);
        $taskView = Permission::updateOrCreate(['name' => 'task-view', 'guard_name' => $guard, 'display_name' => 'View Task']);
        $taskViewAll = Permission::updateOrCreate(['name' => 'task-view-all', 'guard_name' => $guard, 'display_name' => 'View All Tasks']);
        $taskGetByCode = Permission::updateOrCreate(['name' => 'task-get-by-code', 'guard_name' => $guard, 'display_name' => 'View Tasks By Code']);
        $taskWorkLogCreate = Permission::updateOrCreate(['name' => 'task-work-log-add', 'guard_name' => $guard, 'display_name' => 'Add Task Work Log']);
        $taskWorkLogUpdate = Permission::updateOrCreate(['name' => 'task-work-log-update', 'guard_name' => $guard, 'display_name' => 'Update Task Work Log']);
        $taskWorkLogDelete = Permission::updateOrCreate(['name' => 'task-work-log-delete', 'guard_name' => $guard, 'display_name' => 'Delete Task Work Log']);
        $taskWorkLogGetById = Permission::updateOrCreate(['name' => 'task-work-log-view', 'guard_name' => $guard, 'display_name' => 'View Task Work Log']);
        $taskCommentCreate = Permission::updateOrCreate(['name' => 'task-comment-add', 'guard_name' => $guard, 'display_name' => 'Add Task Comment']);
        $taskCommentUpdate = Permission::updateOrCreate(['name' => 'task-comment-update', 'guard_name' => $guard, 'display_name' => 'Update Task Comment']);
        $taskCommentDelete = Permission::updateOrCreate(['name' => 'task-comment-delete', 'guard_name' => $guard, 'display_name' => 'Delete Task Comment']);
        $taskTimelineGetById = Permission::updateOrCreate(['name' => 'task-timeline-view', 'guard_name' => $guard, 'display_name' => 'Task Timeline get By Id']);
        $kanbanTaskGetByCode = Permission::updateOrCreate(['name' => 'kanban-task-get-by-code', 'guard_name' => $guard, 'display_name' => 'Kanban Task get By Code']);
        $taskPermissions = [$taskCreate, $taskUpdate, $taskDelete, $taskView, $taskViewAll, $taskGetByCode, $taskWorkLogCreate, $taskWorkLogUpdate, $taskWorkLogDelete, $taskCommentCreate, $taskCommentUpdate, $taskCommentDelete, $taskWorkLogGetById, $taskTimelineGetById, $kanbanTaskGetByCode];

        // media
        $mediaUpload = Permission::updateOrCreate(['name' => 'media-upload', 'guard_name' => $guard, 'display_name' => 'Upload Media']);
        $mediaPermissions = [$mediaUpload];

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions([$workspacePermissions, $projectPermissions, $taskPermissions, $mediaPermissions, $taskStatusPermissions]);

        $workspaceAdmin = Role::firstOrCreate(['name' => 'Workspace Admin']);
        $workspaceAdmin->syncPermissions([$workspacePermissions, $projectPermissions, $taskPermissions, $mediaPermissions, $taskStatusPermissions]);

        $workspaceMember = Role::firstOrCreate(['name' => 'Workspace Member']);
        $workspaceMember->syncPermissions([$workspaceView, $projectView, $taskPermissions, $taskPermissions, $mediaPermissions, $taskStatusPermissions]);

        Schema::enableForeignKeyConstraints();
    }
}
