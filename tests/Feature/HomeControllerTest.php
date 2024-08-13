<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    /** @test */
    public function home_page_is_publicly_accessible(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(Response::HTTP_OK);
    }
}
