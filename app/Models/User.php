<?php

namespace App\Models;

use App\Observers\UserObserver;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

#[ObservedBy([UserObserver::class])]

class User extends \TCG\Voyager\Models\User implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'showTutorial',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    public function gravatarUrl($size = 80)
    {
        $email = strtolower(trim($this->email));
        $hash = md5($email);
        $default = config('academy.default_gravatar');

        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d={$default}&r=pg";
    }

    public function exams(): HasMany
    {
        return $this->hasMany(\App\Models\Set::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Question::class, 'user_question')->withPivot('score', 'next_at');
    }

    public function tests(): HasMany
    {
        return $this->hasMany(\App\Models\Test::class);
    }

    public function records(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Set::class, 'exam_records', 'user_id', 'set_id')->withPivot('times_taken', 'recent_average', 'mastery_apprentice_count', 'mastery_familiar_count', 'mastery_proficient_count', 'mastery_mastered_count', 'last_completed', 'highest_mastery');
    }

    public function credit(): HasOne
    {
        return $this->hasOne(\App\Models\Credit::class);
    }
}
