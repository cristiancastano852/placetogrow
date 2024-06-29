<?php

namespace App\Policies;

use App\Constants\PermissionSlug;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionSlug::ROLE_PERMISSION_VIEW);
    }

    public function view(User $user, User $model): bool
    {
    }


    public function create(User $user): bool
    {
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo(PermissionSlug::ROLE_PERMISSION_UPDATE);
    }

    public function delete(User $user, User $model): bool
    {
    }

}
