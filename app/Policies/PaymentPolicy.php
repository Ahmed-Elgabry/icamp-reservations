<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('payments.index');
    }

    public function view(User $user, Payment $payment): bool
    {
        return $user->hasPermissionTo('payments.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('payments.create');
    }

    public function update(User $user, Payment $payment): bool
    {
        return $user->hasPermissionTo('payments.edit');
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->hasPermissionTo('payments.destroy');
    }
}
