<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Question::class, 'test_question');
    }

    public function set(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Set::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getEndAtAttribute($time)
    {
        $ended = new Carbon($time);
        $diff = $ended->diffForHumans();

        return $diff;
    }
}
