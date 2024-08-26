<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\Product;
use App\Models\CreditHistory;

class ApplyCreditsToUser 
{
    public static function execute(User $user, $creditAmounts) {
        $credits = $user->credit;

        $credits->architect += $creditAmounts['architect'];
        $credits->study += $creditAmounts['study'];
        
        $credits->save();
    }
}