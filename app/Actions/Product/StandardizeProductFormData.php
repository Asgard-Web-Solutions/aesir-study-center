<?php

namespace App\Actions\Product;

use DB;
use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\ProductDetailsRequest;

class StandardizeProductFormData 
{
    public static function execute(ProductDetailsRequest $request) {
        $validated = $request->validated();

        $validated['isSubscription'] = (isset($request->isSubscription)) ? 1 : 0;
        $validated['annual_price'] = $request->annual_price ?? 000.00;

        return $validated;
    }
}