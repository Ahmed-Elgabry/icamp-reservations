<?php

namespace App\Policies;

use App\Models\ExpenseItem;
use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpenseItemPolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('expense-items.index');
    }

    public function view(User $user, ExpenseItem $expenseItem): bool
    {
        return $user->hasPermissionTo('expense-items.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('expense-items.create');
    }

    public function update(User $user, ExpenseItem $expenseItem): bool
    {
        return $user->hasPermissionTo('expense-items.edit');
    }

    public function delete(User $user, ExpenseItem $expenseItem): bool
    {
        return $user->hasPermissionTo('expense-items.destroy');
    }
}
