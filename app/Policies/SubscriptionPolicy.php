<?php

namespace App\Policies;

use App\Constants\Roles;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function view(User $user, Subscription $subscription): bool
    {
        if ($user->hasRole(Roles::ADMIN->value)) {
            return true;
        }

        if ($user->hasRole(Roles::GUEST->value)) {
            return $subscription->user_id === $user->id;
        }

        if ($user->hasRole(Roles::CUSTOMER->value)) {
            return $subscription->microsite->user_id === $user->id;
        }

        return false;
    }
}
