<?php

namespace App\Observers;

use App\Models\Credit;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $credit = new Credit([
            'architect' => config('mage.default_architect_credits'),
            'publish' => config('mage.default_publish_credits'),
            'question' => config('mage.default_question_credits'),
            'study' => config('mage.default_study_credits'),
        ]);

        $user->credit()->save($credit);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
