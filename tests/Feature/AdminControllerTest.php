<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    // DONE: Create an ACP page
    /** @test */
    public function acp_page_exists() {
        $this->CreateUserAndAuthenticate();

        $response = $this->get(route('admin.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('admin.index');
    }

    // DONE: Create a User List page
    /** @test */
    public function acp_links_to_users_page() {
        $this->CreateUserAndAuthenticate();

        $response = $this->get(route('admin.index'));

        $response->assertSee(route('admin.users'));
    }

    /** @test */
    public function users_page_loads() {
        $this->CreateUserAndAuthenticate();

        $response = $this->get(route('admin.users'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('admin.users');
    }

    /** @test */
    public function users_are_shown_on_users_page() {
        $user = $this->CreateUserAndAuthenticate();

        $response = $this->get(route('admin.users'));

        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    // TODO: Create a User Manage page

    // TODO: Set a user as an admin in their manage page -- Create and use the field isAdmin

    // TODO: Only admins can access the ACP page
}
