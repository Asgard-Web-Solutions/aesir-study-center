<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\Product;
use App\Models\CreditHistory;

class ApplyProductToUser 
{
    public static function execute(User $user, Product $product, $title, $reason) {
        $credits = $user->credit;
        $credits->architect += $product->architect_credits;
        $credits->study += $product->study_credits;
        $credits->save();

        $history = new CreditHistory();
        $history->user_id = $user->id;
        $history->title = $title;
        $history->reason = $reason;
        $history->product_id = $product->id;
        $history->architect_change = $product->architect_credits;
        $history->study_change = $product->study_credits;
        $history->save();
    }
}