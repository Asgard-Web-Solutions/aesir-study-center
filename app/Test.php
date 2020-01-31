<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    public function questions()
    {
        return $this->belongsToMany('App\Question', 'test_question');
    }

    public function set()
    {
        return $this->belongsTo('App\Set');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getEndAtAttribute($time)
    {
        $ended = new Carbon($time);
        $diff = $ended->diffForHumans();

        return $diff;
    }

}
