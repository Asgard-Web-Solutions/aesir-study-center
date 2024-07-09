<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PracticeControllerTest extends TestCase
{
    public function test_default_page_has_link_to_practice() {
        $user = $this->CreateUserAndAuthenticate();
        $set = $this->CreateSet();
        $test = $this->TakeTest([
            'user' => $user->id,
            'exam' => $set->id,
        ]);

        $response = $this->get(route('user-home'));

        $response->assertSee(route('practice-start', $set));
    }
}
