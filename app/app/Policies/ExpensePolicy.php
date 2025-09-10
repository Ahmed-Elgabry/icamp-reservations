<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('expenses.index');
    }

    public function view(User $user, Expense $expense): bool
    {
        return $user->hasPermissionTo('expenses.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('expenses.create');
    }

    public function update(User $user, Expense $expense): bool
    {
        return $user->hasPermissionTo('expenses.edit');
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $user->hasPermissionTo('expenses.destroy');
    }

}
