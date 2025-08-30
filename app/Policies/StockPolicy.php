<?php

namespace App\Policies;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('stocks.index');
    }

    public function view(User $user, Stock $stock): bool
    {
        return $user->hasPermissionTo('stocks.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('stocks.create');
    }

    public function update(User $user, Stock $stock): bool
    {
        return $user->hasPermissionTo('stocks.edit');
    }

    public function delete(User $user, Stock $stock): bool
    {
        return $user->hasPermissionTo('stocks.destroy');
    }
}
