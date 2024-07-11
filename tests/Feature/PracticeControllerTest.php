<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PracticeControllerTest extends TestCase
{
    // Disabled test because we're not working on this quite yet
    public function default_page_has_link_to_practice() {
        $user = $this->CreateUserAndAuthenticate();
        $set = $this->CreateSet();
        $test = $this->TakeTest([
            'user' => $user->id,
            'exam' => $set->id,
        ]);

        $response = $this->get(route('user-home'));

        $response->assertSee(route('practice-start', $set));
    }

    // Disabled 
    public function practice_start_page_loads() {
        $user = $this->CreateUserAndAuthenticate();
        $set = $this->CreateSet();

        $response = $this->get(route('practice-start', $set));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('practice.start');
    }

    // Going to the start page redirects to a configuration page if there is no configuration saved for this ExamSet

    // Going to the start page starts the practice session if the configuration is already set

    // Saving the configuration immediately goes to start the practice session

    // Verify the data saves properly, order of questions, etc

    // Selecting only certain groups for your practice session means only those questions are selected for practice

    // From teh test page a person can go back to the configuration page to change their settings

    // Changing configuration settings during a practice session starts a new session

    // Ending the practice session clears the question order from the table

    // Going to a practice session while there is no question order starts a brand new practice session with the previous settings

    // Display the current level of mastery for each question (in the "answer" section)

    // ----- page permissions -----

    // Guests redirected to login page

    // User must have access to the ExamSet to be able to practice it
}
