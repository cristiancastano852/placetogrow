<?php

namespace App\Policies;

use App\Constants\PermissionSlug;
use App\Constants\Roles;
use App\Models\User;

class ImportPolicy
{
    public function view(User $user): bool
    {
        if ($user->hasRole(Roles::ADMIN->value)) {
            return true;
        }

        return $user->hasPermissionTo(PermissionSlug::IMPORT_INVOICES->value);
    }
}
