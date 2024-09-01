<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'stripe_session'];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
        ];
    }

    public function user(): BelongsTo 
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
