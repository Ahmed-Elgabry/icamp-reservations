<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('services.index');
    }

    public function view(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('services.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('services.create');
    }

    public function update(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('services.edit');
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('services.destroy');
    }

    public function move(User $user): bool
    {
        return $user->hasPermissionTo('services.edit');
    }
}
