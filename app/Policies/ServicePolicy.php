<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('services.index') || $user->hasPermissionTo('camp-types.index');
    }

    public function view(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('services.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('services.create') || $user->hasPermissionTo('camp-types.create');
    }

    public function update(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('services.edit') || $user->hasPermissionTo('camp-types.edit');
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('services.destroy') || $user->hasPermissionTo('camp-types.destroy');
    }

    public function move(User $user): bool
    {
        return $user->hasPermissionTo('services.edit') || $user->hasPermissionTo('camp-types.edit');
    }
}
