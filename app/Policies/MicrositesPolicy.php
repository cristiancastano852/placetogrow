<?php

namespace App\Policies;

use App\Constants\PermissionSlug;
use App\Models\Microsites;
use App\Models\User;

class MicrositesPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionSlug::MICROSITES_VIEW_ANY->value);
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo(PermissionSlug::MICROSITES_VIEW->value);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionSlug::MICROSITES_CREATE->value);
    }

    public function update(User $user, Microsites $microsites): bool
    {

        if ($microsites->user_id !== $user->id && ! $user->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        return $user->hasPermissionTo(PermissionSlug::MICROSITES_UPDATE->value);
    }

    public function delete(User $user, Microsites $microsites): bool
    {
        return $user->hasPermissionTo(PermissionSlug::MICROSITES_DELETE->value);
    }
}
