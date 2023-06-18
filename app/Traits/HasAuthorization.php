<?php
namespace App\Traits;

use Auth;
use App\Models\User;
use App\Models\Permission;
use App\Models\PermissionAssignment;

trait HasAuthorization
{
    public function loadPermissions(): array
    {
        $permissions = [];
        foreach ($this->groupMembersFromMemberable as $gm) {
            $group = $gm->group;
            $permissions =
                array_merge($permissions, $group->permissions());
        }
        foreach ($this->permissionAssignments as $pa) {
            $permissions[] = $pa->permission;
        }
        return array_map(
            fn($p) => $p->guard_name,
            $permissions
        );
    }

    public function hasPermission($permissionString): bool
    {
        if (Auth::user() == $this) {
            if (session('permissions')) {
                $permissionStrings = session('permissions');
            } else {
                $permissionStrings = $this->loadPermissions();
                session(['permissions' => $permissionStrings]);
            }
        } else {
            $permissionStrings = $this->loadPermissions();
        }

        return in_array($permissionString, $permissionStrings);
    }

    public function giveDirectPermission($permission_names): void
    {
        foreach ((array) $permission_names as $permission_name) {
            $perm_id = Permission::byGuardName($permission_name)->id;
            PermissionAssignment::create([
                'permission_id' => $perm_id,
                'assignable_type' => User::class,
                'assignable_id' => $this->id,
            ]);
        }
    }
}