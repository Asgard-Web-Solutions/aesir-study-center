<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

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
}
