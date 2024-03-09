<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function question(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Question::class);
    }
}
