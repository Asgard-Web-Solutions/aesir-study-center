<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Set extends Model
{
    use HasFactory;

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

    // User that created/owns this exam set
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // User that took this exam set
    public function records(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'exam_records', 'set_id', 'user_id')->withPivot('times_taken', 'recent_average', 'mastery_apprentice_count', 'mastery_familiar_count', 'mastery_proficient_count', 'mastery_mastered_count', 'last_completed', 'highest_mastery');
    }

    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'exam_sessions', 'set_id', 'user_id')->withPivot('date_completed', 'questions_array');
    }
}
