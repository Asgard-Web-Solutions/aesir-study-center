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
            'publish' => config('mage.default_publish_credits'),
            'question' => config('mage.default_question_credits'),
            'study' => config('mage.default_study_credits'),
        ]);

        $user->credit()->save($credit);
    }
}
