<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Log;

trait SuperAdminTrait
{
        /**
     * Super admin before check - bypass all policies for user ID = 1 only
     */
    public function before(User $user, $ability)
    {
        if ($user->id == 1) {
            return true;
        }

        return null;
    }
}
