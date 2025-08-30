<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('orders.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('orders.show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('orders.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('orders.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('orders.destroy');
    }

    /**
     * Determine whether the user can view reports.
     */
    public function reports(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('orders.reports');
    }

    /**
     * Determine whether the user can view quotes.
     */
    public function quotes(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('orders.quote');
    }
}
