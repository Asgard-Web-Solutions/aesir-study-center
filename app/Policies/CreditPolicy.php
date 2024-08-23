<?php

namespace App\Policies;

use App\Models\Credit;
use App\Models\User;

class CreditPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Credit $credit): bool
    {
        return $user->id == $credit->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Credit $credit): bool
    {
        //
    }

    public function gift(User $user, Credit $credit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Credit $credit): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Credit $credit): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Credit $credit): bool
    {
        //
    }
}
