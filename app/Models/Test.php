<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    public function questions()
    {
        return $this->belongsToMany(\App\Models\Question::class, 'test_question');
    }

    public function set()
    {
        return $this->belongsTo(\App\Models\Set::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getEndAtAttribute($time)
    {
        $ended = new Carbon($time);
        $diff = $ended->diffForHumans();

        return $diff;
    }
}
