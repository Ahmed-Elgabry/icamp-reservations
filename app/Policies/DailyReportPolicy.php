<?php

namespace App\Policies;

use App\Models\DailyReport;
use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailyReportPolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('daily-reports.index');
    }

    public function view(User $user, DailyReport $dailyReport): bool
    {
        return $user->hasPermissionTo('daily-reports.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('daily-reports.create');
    }

    public function update(User $user, DailyReport $dailyReport): bool
    {
        return $user->hasPermissionTo('daily-reports.edit');
    }

    public function delete(User $user, DailyReport $dailyReport): bool
    {
        return $user->hasPermissionTo('daily-reports.destroy');
    }
}
