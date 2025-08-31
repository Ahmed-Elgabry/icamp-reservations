<?php

namespace App\Policies;

use App\Models\Survey;
use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurveyPolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('surveys.index');
    }

    public function view(User $user, Survey $survey): bool
    {
        return $user->hasPermissionTo('surveys.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('surveys.create');
    }

    public function update(User $user, Survey $survey): bool
    {
        return $user->hasPermissionTo('surveys.edit');
    }

    public function delete(User $user, Survey $survey): bool
    {
        return $user->hasPermissionTo('surveys.destroy');
    }

    public function export(User $user): bool
    {
        return $user->hasPermissionTo('surveys.index');
    }
}
