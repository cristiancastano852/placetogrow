<?php

namespace App\Actions\RolePermissions;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UpdateRolesAction
{
    public function execute(User $user, $roles): void
    {
        $user->syncRoles($roles);
    }
    
}
