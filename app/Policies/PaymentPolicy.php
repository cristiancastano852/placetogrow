<?php

namespace App\Policies;

use App\Constants\Roles;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function view(User $user, Payment $payment): bool
    {
        if ($user->hasRole(Roles::ADMIN->value)) {
            return true;
        }

        if ($user->hasRole(Roles::CUSTOMER->value)) {
            return $payment->microsite->user_id === $user->id;
        }

        if ($user->hasRole(Roles::GUEST->value)) {
            return $payment->user_id === $user->id;
        }

        return false;
    }
}
