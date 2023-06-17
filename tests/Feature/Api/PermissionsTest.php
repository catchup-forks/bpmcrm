<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Permission;
use App\Models\PermissionAssignment;
use Tests\Feature\Shared\RequestHelper;
use \PermissionSeeder;

class PermissionsTest extends TestCase
{
    use RequestHelper;

    protected function withUserSetup()
    {
        $this->user->is_administrator = false;
        $this->user->save();

        (new PermissionSeeder)->run($this->user);

        $create_process_perm = Permission::byGuardName('processes.create');
        $show_process_perm   = Permission::byGuardName('processes.show');
        $update_process_perm = Permission::byGuardName('processes.update');

        $admin_group = $this->admin_group =
            factory(Group::class)->create(['name' => 'Admin']);
        $super_admin_group =
            factory(Group::class)->create(['name' => 'Super Admin']);

        factory(GroupMember::class)->create([
            'member_id' => $this->user->id,
            'member_type' => User::class,
            'group_id'  => $super_admin_group->id,
        ]);

        factory(GroupMember::class)->create([
            'member_id' => $super_admin_group->id,
            'member_type' => Group::class,
            'group_id'  => $admin_group->id,
        ]);

        factory(PermissionAssignment::class)->create([
            'assignable_type' => Group::class,
            'assignable_id' => $admin_group->id,
            'permission_id' => $create_process_perm->id,
        ]);

        factory(PermissionAssignment::class)->create([
            'assignable_type' => Group::class,
            'assignable_id' => $super_admin_group->id,
            'permission_id' => $update_process_perm->id,
        ]);

        factory(PermissionAssignment::class)->create([
            'assignable_type' => get_class($this->user),
            'assignable_id' => $this->user->id,
            'permission_id' => $show_process_perm->id,
        ]);
        $this->user->giveDirectPermission($show_process_perm->guard_name);

        $this->process = factory(\app\Models\Process::class)->create([
            'name' => 'foo',
        ]);
    }

    public function testApiPermissions()
    {
        $response = $this->apiCall('GET', '/processes');
        $response->assertStatus(200);

        $response = $this->apiCall('GET', '/processes/' . $this->process->id);
        $response->assertStatus(200);

        $destroy_process_perm = Permission::byGuardName('processes.destroy');
        Group::where('name', 'All Permissions')
            ->firstOrFail()
            ->permissionAssignments()
            ->where('permission_id', $destroy_process_perm->id)
            ->delete();

        $this->user->refresh();
        $this->flushSession();

        $response = $this->apiCall('DELETE', '/processes/' . $this->process->id);
        $response->assertStatus(403);
        $response->assertSee('Not authorized');

        factory(PermissionAssignment::class)->create([
            'assignable_type' => Group::class,
            'assignable_id' => $this->admin_group->id,
            'permission_id' => Permission::byGuardName('processes.destroy')->id,
        ]);

        $this->user->refresh();
        $this->flushSession();

        $response = $this->apiCall('DELETE', '/processes/' . $this->process->id);
        $response->assertStatus(204);
    }
}
