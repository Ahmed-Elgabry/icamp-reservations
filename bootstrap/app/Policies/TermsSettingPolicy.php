<?php

namespace App\Policies;

use App\Models\TermsSittng;
use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class TermsSettingPolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('terms-settings.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TermsSittng $termsSetting): bool
    {
        return $user->hasPermissionTo('terms-settings.show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('terms-settings.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TermsSittng $termsSetting): bool
    {
        return $user->hasPermissionTo('terms-settings.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TermsSittng $termsSetting): bool
    {
        return $user->hasPermissionTo('terms-settings.destroy');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TermsSittng $termsSetting): bool
    {
        return $user->hasPermissionTo('terms-settings.edit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TermsSittng $termsSetting): bool
    {
        return $user->hasPermissionTo('terms-settings.destroy');
    }
}
