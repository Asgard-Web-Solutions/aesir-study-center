<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use DB;
use Tests\TestCase;

class ExamSessionTest extends TestCase
{
    // DONE: Create an ExamSession when a user starts a new instance of a test
    /** @test */
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

    /** @test */
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

    // TODO: Update the ExamSession when moving to a new question

    // TODO: Track Mastery Progress for this session after each question

    // TODO: Finalize the ExamSession at the end of the test

    // TODO: Show a history of exam sessions that you have taken for an exam
}
