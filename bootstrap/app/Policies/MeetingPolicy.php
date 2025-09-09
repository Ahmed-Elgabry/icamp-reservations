<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;
use App\Traits\SuperAdminTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingPolicy
{
    use HandlesAuthorization, SuperAdminTrait;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('meetings.index');
    }

    public function view(User $user, Meeting $meeting): bool
    {
        return $user->hasPermissionTo('meetings.show');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('meetings.create');
    }

    public function update(User $user, Meeting $meeting): bool
    {
        return $user->hasPermissionTo('meetings.edit');
    }

    public function delete(User $user, Meeting $meeting): bool
    {
        return $user->hasPermissionTo('meetings.destroy');
    }
}
