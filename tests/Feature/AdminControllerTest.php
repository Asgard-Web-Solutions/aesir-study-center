<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // TODO: Create an ACP page

    // TODO: Create a User List page

    // TODO: Create a User Mange page

    // TODO: Set a user as an admin in their manage page -- Create and use the field isAdmin

    // TODO: Only admins can access the ACP page
}
