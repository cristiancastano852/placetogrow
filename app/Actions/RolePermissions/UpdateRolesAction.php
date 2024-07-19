<?php

namespace App\Actions\RolePermissions;

use App\Models\User;

class UpdateRolesAction
{
    public function execute(User $user, $roles): void
    {
        $user->syncRoles($roles);
    }
}
