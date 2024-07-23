<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
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
    /** @test */
    public function acp_page_exists() {
        $this->CreateUserAndAuthenticate();

        $response = $this->get(route('admin.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    // TODO: Create a User List page

    // TODO: Create a User Mange page

    // TODO: Set a user as an admin in their manage page -- Create and use the field isAdmin

    // TODO: Only admins can access the ACP page
}
