<?php

namespace Tests;

use App\Models\Credit;
use App\Models\User;

trait GiveUserCredits
{
    public function GiveUserCredits(User $user): void
    {
        $credit = new Credit([
            'architect' => config('mage.default_architect_credits'),
            'study' => config('mage.default_study_credits'),
        ]);

        $user->credit()->save($credit);
    }
}
