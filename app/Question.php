<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function set()
    {
        return $this->belongsTo('App\Set');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public function tests()
    {
        return $this->belongsToMany('App\Question', 'test_question');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_question');
    }
}
