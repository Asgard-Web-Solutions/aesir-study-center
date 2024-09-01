<?php

namespace App\Actions\User;

use App\Mail\AcolytePurchaseReceipt;
use App\Models\User;
use App\Models\Product;
use App\Models\CreditHistory;
use Illuminate\Support\Facades\Mail;

class ApplyProductToUser 
{
    public static function execute(User $user, Product $product, $title, $reason) {
        $credits['architect'] = $product->architect_credits;
        $credits['study'] = $product->study_credits;

        ApplyCreditsToUser::execute($user, $credits);

        $history = RecordCreditHistory::execute($user, $title, $reason, $credits);
        $history->update(['product_id' => $product->id]);

        Mail::to($user->email)->send(new AcolytePurchaseReceipt($history));

        return $history;
    }
}