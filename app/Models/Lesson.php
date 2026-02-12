<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'set_id',
        'name',
    ];

    public function set(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Set::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(\App\Models\Question::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(\App\Models\Group::class);
    }
}
