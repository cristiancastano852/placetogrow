<?php

namespace App\Policies;

use App\Constants\PermissionSlug;
use App\Constants\Roles;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(Roles::ADMIN->value)) {
            return true;
        }

        return $user->hasPermissionTo(PermissionSlug::INVOICES_VIEW_ANY->value);

    }
}
