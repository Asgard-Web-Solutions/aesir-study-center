<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function set(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Set::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(\App\Models\Answer::class);
    }

    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Question::class, 'test_question');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_question');
    }
}
