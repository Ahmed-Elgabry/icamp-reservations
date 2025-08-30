<?php

namespace App\Policies;

use App\Models\EquipmentDirectory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('equipment-directories.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EquipmentDirectory $equipment): bool
    {
        return $user->hasPermissionTo('equipment-directories.index');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('equipment-directories.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EquipmentDirectory $equipment): bool
    {
        return $user->hasPermissionTo('equipment-directories.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EquipmentDirectory $equipment): bool
    {
        return $user->hasPermissionTo('equipment-directories.destroy');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EquipmentDirectory $equipment): bool
    {
        return $user->hasPermissionTo('equipment-directories.destroy');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EquipmentDirectory $equipment): bool
    {
        return $user->hasPermissionTo('equipment-directories.destroy');
    }
}
