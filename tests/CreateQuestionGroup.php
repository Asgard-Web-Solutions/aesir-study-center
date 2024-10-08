<?php

namespace Tests;

use DB;
use stdClass;

trait CreateQuestionGroup
{
    public function CreateQuestionGroup($attributes = []): stdClass
    {
        DB::table('groups')->insert([
            'set_id' => $attributes['set_id'],
            'name' => $attributes['name'],
        ]);

        $group = DB::table('groups')->where('set_id', $attributes['set_id'])->where('name', $attributes['name'])->first();

        return $group;
    }
}
