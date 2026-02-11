<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()) {
            return false;
        }

        if (auth()->user()->isAdmin) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:3|max:255|nullable',
            'price' => 'required|decimal:0,2|min:0|max:9999.99',
            'isSubscription' => 'sometimes|nullable',
            'annual_price' => 'nullable|decimal:0,2|min:0|max:9999.99',
            'stripe_product_id' => 'string|min:0|max:255|nullable',
            'stripe_price_id' => 'string|min:0|max:255|nullable',
            'stripe_annual_price_id' => 'string|min:0|max:255|nullable',
            'architect_credits' => 'required|integer|min:0|max:99',
            'study_credits' => 'required|integer|min:0|max:99',
        ];
    }
}
