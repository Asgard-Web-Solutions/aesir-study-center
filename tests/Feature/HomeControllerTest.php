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

    /** @test */
    public function public_test_page_publicly_accessible() {
        $response = $this->get(route('public-exams'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('home.public');
    }

    /** @test */
    public function public_page_shows_public_tests() {
        $exam = $this->CreateSet(['visibility' => 1]);

        $response = $this->get(route('public-exams'));

        $response->assertSee($exam->name);
    }
}
