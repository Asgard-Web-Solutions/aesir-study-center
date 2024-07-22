<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    /** @test */
    public function home_page_is_publicly_accessible() {
        $response = $this->get(route('home'));

        $response->assertStatus(Response::HTTP_OK);
    }
}
