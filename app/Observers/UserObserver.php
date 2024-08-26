<?php

namespace App\Observers;

use App\Actions\User\RecordCreditHistory;
use App\Models\Credit;
use App\Models\CreditHistory;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $credits['architect'] = config('mage.default_architect_credits');
        $credits['study'] = config('mage.default_study_credits');

        $credit = new Credit([
            'architect' => $credits['architect'],
            'study' => $credits['study'],
        ]);

        $user->credit()->save($credit);

        $history = RecordCreditHistory::execute($user, 'Acolyte Enrollment', 'Credits received for enrolling at Acolyte Academy.', $credits);
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
