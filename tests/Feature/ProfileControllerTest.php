<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use DB;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;
    
    // DONE: Create an ExamPortal page that shows user ExamRecords
    /** 
     * @test 
     * @dataProvider pagesDataProvider
     * */
    public function profile_pages_load($route, $view) {
        $user = $this->CreateUserAndAuthenticate();

        $useRoute = 'profile.' . $route;
        $verifyView = 'profile.' . $view;
        
        $response = $this->get(route($useRoute));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs($verifyView);
    }

    /** @test */
    public function exam_portal_shows_user_tests() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);

        $response = $this->get(route('profile.exams'));

        $response->assertSee($exam->name);
    }
   
    // DONE: Create an ExamManage page that shows the exams that you have created, probably in a list instead of the cards
    /** @test */
    public function myexams_shows_list_of_your_exams() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);

        $response = $this->get(route('profile.myexams'));

        $response->assertSee($exam->name);
    }

    /** @test */
    public function myexams_shows_link_to_create_exam() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);

        $response = $this->get(route('profile.myexams'));

        $response->assertSee(route('exam-create'));
    }
    
    // DONE: Create a profile edit page so users can actually change their name, email, and password
    /** @test */
    public function profile_index_page_loads() 
    {
        $user = $this->CreateUserAndAuthenticate();
        
        $response = $this->get(route('profile.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('profile.index');
        $response->assertSee($user->email);
    }

    /** @test */
    public function profile_save_page_updates_database() {
        $user = $this->CreateUserAndAuthenticate();

        $data = [
            'name' => 'Captain Kirk',
            'email' => 'kirk@enterprise.org',
            'showTutorial' => 1,
        ];

        $response = $this->post(route('profile.update'), $data);

        $data['id'] = $user->id;
        $this->assertDatabaseHas('users', $data);
    }

    /** @test */
    public function public_profile_page_shows_up() {
        $user = $this->CreateUser();
        $authedUser = $this->CreateUserAndAuthenticate();

        $response = $this->get(route('profile.view', $user));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('profile.view');
    }

    /** @test */
    public function profile_page_shows_public_tests() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id, 'visibility' => 1]);

        $response = $this->get(route('profile.view', $user));

        $response->assertSee($exam->name);
    }

    /** @test */
    public function profile_page_does_not_show_private_tests() {
        $user = $this->CreateUserAndAuthenticate();
        $examOwner = $this->CreateUser();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 0]);

        $response = $this->get(route('profile.view', $examOwner));

        $response->assertDontSee($exam->name);
    }

    /** @test */
    public function exams_taken_show_up_on_profile_page() {
        $user = $this->CreateUserAndAuthenticate();
        $examOwner = $this->CreateUser();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 1]);
        DB::table('exam_records')->insert([
            'user_id' => $user->id,
            'set_id' => $exam->id,
            'times_taken' => 1,
        ]);

        $response = $this->get(route('profile.view', $user));

        $response->assertSee($exam->name);
    }

    // TODO: Calculate the full mastery that has been achieved for each test

    // TODO: Display an icon on the Exam card for any mastery level that has been fully achieved.

    // TODO: Show a list of Exam Masteries on the users profile page

    // TODO: Show a list of who has mastered an exam on the Exam View page

    /** ========== DataProvider Methods ========== */
    private static function pagesDataProvider() {
        /** 
         * Route
         * View
         */
        return [
            ['exams', 'exams'],
            ['myexams', 'myexams'],
        ];
    }
}
