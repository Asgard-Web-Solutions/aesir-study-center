<?php

namespace App\Models;

use App\Observers\QuestionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([QuestionObserver::class])]

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'set_id',
        'group_id',
        'lesson_id',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Lesson::class);
    }

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
        return $this->belongsToMany(\App\Models\User::class, 'user_question')->withPivot('reviewFlagged');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Group::class);
    }

    public function insights(): HasMany
    {
        return $this->HasMany(Insight::class);
    }
}
