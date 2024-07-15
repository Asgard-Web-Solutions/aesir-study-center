<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    /** @test */
    public function profile_index_page_loads() 
    {
        $user = $this->CreateUserAndAuthenticate();
        
        $response = $this->get(route('profile.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('profile.index');
    }

}
