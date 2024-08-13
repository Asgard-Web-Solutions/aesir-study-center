<?php

namespace App\Policies;

use App\Models\ExamPractice;
use App\Models\User;

class ExamPracticePolicy
{
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
    public function view(User $user, ExamPractice $examPractice): bool
    {
        return $user->id == $examPractice->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ExamPractice $examPractice): bool
    {
        return $user->id == $examPractice->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ExamPractice $examPractice): bool
    {
        return $user->id == $examPractice->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ExamPractice $examPractice): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ExamPractice $examPractice): bool
    {
        //
    }
}
