<?php

namespace App\Policies;

use App\Models\CampReport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('camp-reports.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CampReport $campReport): bool
    {
        return $user->hasPermissionTo('camp-reports.show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('camp-reports.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CampReport $campReport): bool
    {
        return $user->hasPermissionTo('camp-reports.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CampReport $campReport): bool
    {
        return $user->hasPermissionTo('camp-reports.destroy');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CampReport $campReport): bool
    {
        return $user->hasPermissionTo('camp-reports.edit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CampReport $campReport): bool
    {
        return $user->hasPermissionTo('camp-reports.destroy');
    }
}
