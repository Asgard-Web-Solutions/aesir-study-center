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
}
