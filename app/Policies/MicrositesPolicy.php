<?php

namespace App\Policies;

use App\Constants\PermissionSlug;
use App\Models\User;
use App\Models\Microsites;

class MicrositesPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionSlug::MICROSITES_VIEW_ANY);
    }

 
    public function view(User $user): bool
    {
        return $user->hasPermissionTo(PermissionSlug::MICROSITES_VIEW);
    }

 
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionSlug::MICROSITES_CREATE);
    }


    public function update(User $user, Microsites $microsites): bool
    {

        if ($microsites->user_id !== $user->id && !$user->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }
        return $user->hasPermissionTo(PermissionSlug::MICROSITES_UPDATE);
    }

    public function delete(User $user, Microsites $microsites): bool
    {
        return $user->hasPermissionTo(PermissionSlug::MICROSITES_DELETE);
    }

}
