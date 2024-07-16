<?php

namespace Tests;

use App\Models\Set;

trait CreateSet
{
    public function CreateSet($attributes = []): Set
    {
        $set = Set::factory()->create($attributes);

        for ($i = 0; $i < 10; $i++) {
            $question = $this->CreateQuestion([
                'set_id' => $set->id,
            ]);
        }

        return $set;
    }
}
