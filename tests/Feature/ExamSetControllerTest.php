<?php

namespace Tests\Feature;

use Config;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ExamSetControllerTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider dataProviderExamPages
     */
    public function validate_that_pages_load_correctly($route, $method, $status, $view): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $data = [];

        $route = 'exam.'.$route;

        if ($method == 'get') {
            $response = $this->get(route($route, $exam));
        } else {
            $response = $this->post(route($route, $exam), $data);
        }

        $response->assertStatus($status);

        if ($status == Response::HTTP_OK) {
            $view = 'exam.'.$view;
            $response->assertViewIs($view);
        }
    }

    /** @test */
    public function public_test_page_publicly_accessible(): void
    {
        $response = $this->get(route('exam.public'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('exam.public');
    }

    /** @test */
    public function public_page_shows_public_tests(): void
    {
        $exam = $this->CreateSet(['visibility' => 1]);

        $response = $this->get(route('exam.public'));

        $response->assertSee($exam->name);
    }

    /** @test */
    public function exam_view_page_loads(): void
    {
        $exam = $this->CreateSet(['visibility' => 1]);

        $response = $this->get(route('exam.view', $exam));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('exam.view');
    }

    /** @test */
    public function exam_view_page_shows_information(): void
    {
        $exam = $this->CreateSet(['visibility' => 1]);

        $response = $this->get(route('exam.view', $exam));

        $response->assertSee($exam->name);
    }

    /** @test */
    public function creating_exams_costs_architect_credits(): void
    {
        Config::set('mage.default_architect_credits', 2);
        $user = $this->CreateUserAndAuthenticate();
        $data = ([
            'name' => 'Test Cost',
            'description' => 'This is just a test',
        ]);

        $response = $this->post(route('exam.store'), $data);

        $verifyData = ([
            'user_id' => $user->id,
            'architect' => config('mage.default_architect_credits') - 1,
        ]);

        $this->assertDatabaseHas('credits', $verifyData);
    }

    /** @test */
    public function creating_exams_does_not_cost_mages_architect_credits(): void
    {
        Config::set('mage.default_architect_credits', 2);
        $user = $this->CreateUserAndAuthenticate(['isMage' => 1]);
        $data = ([
            'name' => 'Test Cost',
            'description' => 'This is just a test',
        ]);

        $response = $this->post(route('exam.store'), $data);

        $verifyData = ([
            'user_id' => $user->id,
            'architect' => config('mage.default_architect_credits'),
        ]);

        $this->assertDatabaseHas('credits', $verifyData);
    }

    /** @test */
    public function adding_questions_costs_question_credits(): void
    {
        Config::set('mage.default_question_credits', 2);
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $data = ([
            'question' => 'Test Cost',
            'answers' => ['This is just a test'],
            'correct' => [1],
        ]);

        $response = $this->post(route('exam.add', $exam), $data);

        $verifyData = ([
            'user_id' => $user->id,
            'question' => config('mage.default_question_credits') - 1,
        ]);

        $this->assertDatabaseHas('credits', $verifyData);
    }

    /** @test */
    public function adding_questions_does_not_cost_mages_question_credits(): void
    {
        Config::set('mage.default_question_credits', 2);
        $user = $this->CreateUserAndAuthenticate(['isMage' => 1]);
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $data = ([
            'question' => 'Test Cost',
            'answers' => ['This is just a test'],
            'correct' => [1],
        ]);

        $response = $this->post(route('exam.add', $exam), $data);

        $verifyData = ([
            'user_id' => $user->id,
            'question' => config('mage.default_question_credits'),
        ]);

        $this->assertDatabaseHas('credits', $verifyData);
    }

    /** @test */
    public function adding_a_question_is_restricted_if_max_limit_reached(): void
    {
        Config::set('test.max_exam_questions', 0);
        $user = $this->CreateUserAndAuthenticate(['isMage' => 1]);
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $data = ([
            'question' => 'Test Cost',
            'answers' => ['This is just a test'],
            'correct' => [1],
        ]);

        $response = $this->post(route('exam.add', $exam), $data);

        $verifyData = ([
            'text' => $data['question'],
        ]);

        $this->assertDatabaseMissing('test_question', $verifyData);
        $response->assertSessionHas('warning');
    }

    public static function dataProviderExamPages()
    {
        /**
         * Route Name
         * Method == get or post
         * Expected Response Status
         * View Name
         */
        return [
            ['view', 'get', Response::HTTP_OK, 'view'],
            ['edit', 'get', Response::HTTP_OK, 'edit'],
        ];
    }
}
