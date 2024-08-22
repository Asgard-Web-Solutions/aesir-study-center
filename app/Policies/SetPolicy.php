<?php

namespace App\Policies;

use App\Enums\Visibility;
use App\Models\Set;
use App\Models\User;

class SetPolicy
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
    public function view(?User $user, Set $set): bool
    {
        if ($set->visibility == Visibility::isPublic->value) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $set->user_id === $user->id;
    }

    public function take(User $user, Set $set): bool
    {
        if ($set->visibility == Visibility::isPublic->value) {
            return true;
        }

        return $set->user_id === $user->id;
    }


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Set $set): bool
    {
        return $set->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Set $set): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Set $set): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Set $set): bool
    {
        //
    }
}
