<?php

namespace Tests;

use App\Models\Set;

trait CreateSet
{
    public function CreateSet($attributes = []): Set
    {
        $set = Set::factory()->create($attributes);
        $question = $this->CreateQuestion([
            'set_id' => $set->id,
        ]);

        return $set;
    }
}
