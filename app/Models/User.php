<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use App\Observers\UserObserver;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasFactory, Billable;

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

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
        return $this->belongsToMany(\App\Models\Question::class, 'user_question')->withPivot('score', 'next_at', 'reviewFlagged');
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

    public function creditHistory(): HasMany
    {
        return $this->hasMany(\App\Models\CreditHistory::class);
    }
}
