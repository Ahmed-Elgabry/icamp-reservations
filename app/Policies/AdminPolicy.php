<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('admins.index');
    }

    public function view(User $user, User $admin): bool
    {
        return $user->hasPermissionTo('admins.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('admins.create');
    }

    public function update(User $user, User $admin): bool
    {
        return $user->hasPermissionTo('admins.edit');
    }

    public function delete(User $user, User $admin): bool
    {
        return $user->hasPermissionTo('admins.destroy');
    }
}
