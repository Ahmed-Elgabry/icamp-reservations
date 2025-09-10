<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {

        return $user->hasPermissionTo('bookings.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('bookings.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('bookings.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('bookings.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('bookings.delete');
    }

    /**
     * Determine whether the user can view reports.
     */
    public function reports(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('bookings.reports');
    }

    /**
     * Determine whether the user can view quotes.
     */
    public function quotes(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('bookings.quote');
    }
}
