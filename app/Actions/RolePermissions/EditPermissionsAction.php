<?php

namespace App\Actions\RolePermissions;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EditPermissionsAction
{
    public function execute(Role $role, array $permissionNames): void
    {
        $existingPermissions = Permission::whereIn('name', $permissionNames)->get();

        $validPermissionNames = $existingPermissions->pluck('name')->toArray();

        $role->syncPermissions($validPermissionNames);
    }
}
