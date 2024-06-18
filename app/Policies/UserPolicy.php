<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     *
     * @param User $admin
     * @param User $user
     * @return bool
     */
    public function delete(User $admin, User $user): bool
    {
        if ($user->id === 1) {
            return false;
        }

        if (!$admin->hasRole('administrator') || $admin->id === $user->id) {
            return false;
        }

        return true;
    }
}
