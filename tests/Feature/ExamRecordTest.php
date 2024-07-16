<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use DB;
use Tests\TestCase;

class ExamRecordTest extends TestCase
{
    // DONE: When a user takes an exam for the first time, create the exam record
    /** @test */
    public function exam_record_created_when_first_taking_an_exam() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $response = $this->get(route('exam-session.start', $exam));

        $data = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ];

        $this->assertDatabaseHas('exam_records', $data);
    }

    /** @test */
    public function exam_record_is_not_duplicated_if_already_exists() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        
        $data = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ];

        DB::table('exam_records')->insert($data);


        $response = $this->get(route('exam-session.start', $exam));

        $this->assertDatabaseCount('exam_records', 1);
    }

    // DONE: Make sure the user has permission to run this exam before starting (start page)
    /** @test */
    public function user_can_start_their_own_private_exam() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $response = $this->get(route('exam-session.start', $exam));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function other_users_cannot_start_private_exams() {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 0]);

        $response = $this->get(route('exam-session.start', $exam));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function other_users_can_start_public_exams() {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 1]);

        $response = $this->get(route('exam-session.start', $exam));

        $response->assertStatus(Response::HTTP_OK);
    }

    // TODO: When a user completes an exam, update the exam record stats

    
    // TODO: Write a command to generate/update the ExamRecord for a single user or all users
    
    
    // TODO: Add Mastery Progress to the exam recordß
}
