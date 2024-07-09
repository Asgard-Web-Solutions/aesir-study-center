<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Set extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'visibility',
    ];
    
    public function questions(): HasMany
    {
        return $this->hasMany(\App\Models\Question::class);
    }

    public function tests(): HasMany
    {
        return $this->hasMany(\App\Models\Test::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(\App\Models\Group::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
