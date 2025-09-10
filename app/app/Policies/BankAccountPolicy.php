<?php

namespace App\Policies;

use App\Models\BankAccount;
use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankAccountPolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('bank-accounts.index');
    }

    public function view(User $user, BankAccount $bankAccount): bool
    {
        return $user->hasPermissionTo('bank-accounts.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('bank-accounts.create');
    }

    public function update(User $user, BankAccount $bankAccount): bool
    {
        return $user->hasPermissionTo('bank-accounts.edit');
    }

    public function delete(User $user, BankAccount $bankAccount): bool
    {
        return $user->hasPermissionTo('bank-accounts.destroy');
    }
}
