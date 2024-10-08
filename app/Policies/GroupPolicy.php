<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Set;
use App\Models\User;

class GroupPolicy
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
    public function view(User $user, Group $group): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Set $set): bool
    {
        return $set->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Group $group): bool
    {
        return $group->set->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Group $group): bool
    {
        return $group->set->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Group $group): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Group $group): bool
    {
        //
    }
}
