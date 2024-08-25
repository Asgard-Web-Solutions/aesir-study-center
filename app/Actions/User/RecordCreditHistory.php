<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\Product;
use App\Models\CreditHistory;

class RecordCreditHistory 
{
    public static function execute(User $user, $title, $reason, $credits) {
        $history = new CreditHistory();
        $history->user_id = $user->id;
        $history->title = $title;
        $history->reason = $reason;
        $history->architect_change = $credits['architect'];
        $history->study_change = $credits['study'];
        $history->save();

        return $history;
    }
}