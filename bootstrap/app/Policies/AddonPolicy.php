<?php

namespace App\Policies;

use App\Models\Addon;
use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddonPolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('addons.index');
    }

    public function view(User $user, Addon $addon): bool
    {
        return $user->hasPermissionTo('addons.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('addons.create');
    }

    public function update(User $user, Addon $addon): bool
    {
        return $user->hasPermissionTo('addons.edit');
    }

    public function delete(User $user, Addon $addon): bool
    {
        return $user->hasPermissionTo('addons.destroy');
    }
}
