<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    public function questions()
    {
        return $this->hasMany(\App\Models\Question::class);
    }

    public function tests()
    {
        return $this->hasMany(\App\Models\Test::class);
    }
}
