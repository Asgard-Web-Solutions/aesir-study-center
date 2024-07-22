<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamSetControllerTest extends TestCase
{
    /** @test */
    public function public_test_page_publicly_accessible() {
        $response = $this->get(route('exam.public'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('exam.public');
    }

    /** @test */
    public function public_page_shows_public_tests() {
        $exam = $this->CreateSet(['visibility' => 1]);

        $response = $this->get(route('exam.public'));

        $response->assertSee($exam->name);
    }

    /** @test */
    public function exam_view_page_loads() {
        $exam = $this->CreateSet(['visibility' => 1]);

        $response = $this->get(route('exam.view', $exam));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('exam.view');
    }

    /** @test */
    public function exam_view_page_shows_information() {
        $exam = $this->CreateSet(['visibility' => 1]);

        $response = $this->get(route('exam.view', $exam));

        $response->assertSee($exam->name);
    }
}
