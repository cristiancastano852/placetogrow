<?php

namespace App\Policies;

use App\Constants\PermissionSlug;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionSlug::USERS_VIEW_ANY->value);
    }

    public function viewAnyPermissions(User $user): bool
    {
        return $user->hasPermissionTo(PermissionSlug::ROLE_PERMISSION_VIEW->value);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionSlug::USERS_CREATE->value);
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo(PermissionSlug::ROLE_PERMISSION_UPDATE->value);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo(PermissionSlug::USERS_DELETE->value);
    }
}
