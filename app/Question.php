<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function set()
    {
        return $this->belongsTo(\App\Set::class);
    }

    public function answers()
    {
        return $this->hasMany(\App\Answer::class);
    }

    public function tests()
    {
        return $this->belongsToMany(\App\Question::class, 'test_question');
    }

    public function users()
    {
        return $this->belongsToMany(\App\User::class, 'user_question');
    }
}
