<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    public function questions()
    {
        return $this->hasMany(\App\Question::class);
    }

    public function tests()
    {
        return $this->hasMany(\App\Test::class);
    }
}
