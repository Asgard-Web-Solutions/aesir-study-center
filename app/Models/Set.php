<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Set extends Model
{
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
}
