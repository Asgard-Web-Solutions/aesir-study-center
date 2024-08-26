<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\Product;
use App\Models\CreditHistory;

class RecordCreditHistory 
{
    public static function execute(User $user, $title, $reason, $credits) {
        
        $architect_change = array_key_exists('architect', $credits) ? $credits['architect'] : 0;
        $study_change = array_key_exists('study', $credits) ? $credits['study'] : 0;
        
        $history = new CreditHistory();
        $history->user_id = $user->id;
        $history->title = $title;
        $history->reason = $reason;
        $history->architect_change = $architect_change;
        $history->study_change = $study_change;
        $history->save();

        return $history;
    }
}