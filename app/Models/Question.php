<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    public function set()
    {
        return $this->belongsTo(\App\Models\Set::class);
    }

    public function answers()
    {
        return $this->hasMany(\App\Models\Answer::class);
    }

    public function tests()
    {
        return $this->belongsToMany(\App\Models\Question::class, 'test_question');
    }

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_question');
    }
}
