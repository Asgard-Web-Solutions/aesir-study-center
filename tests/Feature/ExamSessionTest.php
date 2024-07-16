<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use DB;
use Tests\TestCase;

class ExamSessionTest extends TestCase
{
    // DONE: Create an ExamSession when a user starts a new instance of a test
    /**  */
    public function exam_session_created_when_first_taking_an_exam() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $response = $this->get(route('exam-session.start', $exam));

        $data = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ];

        $this->assertDatabaseHas('exam_sessions', $data);
    }

    /**  */
    public function exam_session_is_not_created_when_an_exam_is_already_in_progress() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $data = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ];
        DB::table('exam_sessions')->insert($data);

        $response = $this->get(route('exam-session.start', $exam));

        $data = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ];

        $this->assertDatabaseCount('exam_sessions', 1);
    }

    // DONE: Exam Configuration Page Loads when starting a new session
    /** @test */
    public function redirected_to_exam_configuration_page_when_starting_a_new_exam_session() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $response = $this->get(route('exam-session.start', $exam));

        $response->assertRedirect(route('exam-session.configure', $exam));
    }

    /** @test */
    public function exam_configuration_page_loads() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $response = $this->get(route('exam-session.configure', $exam));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('exam_session.configure');
    }

    /** @test */
    public function exam_configuration_page_not_allowed_for_private_exams() {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 0]);

        $response = $this->get(route('exam-session.configure', $exam));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function exam_configuration_page_is_allowed_for_public_exams() {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 1]);

        $response = $this->get(route('exam-session.configure', $exam));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function exam_configuration_page_loads_exam_set_data() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $response = $this->get(route('exam-session.configure', $exam));

        $response->assertSee($exam->name);
    }

    // TODO: Store configuration for this exam

    // TODO: Start the actual exam

    // TODO: Finalize the ExamSession at the end of the test




    // TODO: Update the ExamSession when moving to a new question

    // TODO: Track Mastery Progress for this session after each question

    // TODO: Show a history of exam sessions that you have taken for an exam
}
