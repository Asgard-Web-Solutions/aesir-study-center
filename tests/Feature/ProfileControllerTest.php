<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    
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
    
    // TODO: Create a profile edit page so users can actually change their name, email, and password
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
        ];

        $response = $this->post(route('profile.update'), $data);

        $data['id'] = $user->id;
        $this->assertDatabaseHas('users', $data);
    }

    // TODO: Profile link shows up in the menu

    // TODO: Make a new home page with some basic info about the site

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
