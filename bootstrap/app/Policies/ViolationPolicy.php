<?php

namespace App\Policies;

use App\Models\Violation;
use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class ViolationPolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('violations.index');
    }

    public function view(User $user, Violation $violation): bool
    {
        return $user->hasPermissionTo('violations.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('violations.create');
    }

    public function update(User $user, Violation $violation): bool
    {
        return $user->hasPermissionTo('violations.edit');
    }

    public function delete(User $user, Violation $violation): bool
    {
        return $user->hasPermissionTo('violations.destroy');
    }
}
