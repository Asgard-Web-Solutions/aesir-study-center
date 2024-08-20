<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'isSubscription',
        'annual_price',
        'stripe_product_id',
        'stripe_price_id',
        'stripe_annual_price_id',
        'architect_credits',
        'study_credits',
    ];
}
