<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Question;
use App\Models\Answer;
use DB;
use Tests\TestCase;

class ExamSessionTest extends TestCase
{
    /** 
     * @test
     * @dataProvider dataProviderExamSessionPages
     */
    public function validate_that_pages_load_correctly($route, $method, $status, $view) {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $data = array();
        
        if ($route == 'answer') {
            $data = [
                'answer-1' => 1,
                'question' => 1,
                'order' => "[1]",
            ];
        }

        $route = 'exam-session.' . $route;

        if ($method == 'get') {
            $response = $this->get(route($route, $exam));
        } else {
            $response = $this->post(route($route, $exam), $data);
        }

        $response->assertStatus($status);

        if ($status == Response::HTTP_OK) {
            $view = 'exam_session.' . $view;
            $response->assertViewIs($view);
        }
    }

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

    // DONE: Store configuration for this exam
    /** @test */
    public function exam_configuration_saves_to_database() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $data = [
            'question_count' => 1,
        ];

        $response = $this->post(route('exam-session.store', $exam), $data);

        $this->assertDatabaseHas('exam_sessions', $data);
    }

    /** @test */
    public function exam_save_page_not_allowed_for_private_exams() {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 0]);

        $data = [
            'question_count' => 1,
        ];

        $response = $this->post(route('exam-session.store', $exam), $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function exam_save_page_is_allowed_for_public_exams() {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 1]);

        $data = [
            'question_count' => 1,
        ];

        $response = $this->post(route('exam-session.store', $exam), $data);
        $session = DB::table('exam_sessions')->where('set_id', $exam->id)->where('user_id', $user->id)->first();

        $response->assertRedirect(route('exam-session.test', $session->id));
    }

    // DONE: Validate data
    /** 
     * @test 
     * @dataProvider dataProviderForExamSessionStoreFormInvalidData
     * */
    public function exam_save_validates_data($field, $value) {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $data = $this->getExamConfigurationFormData();
        $data[$field] = $value;

        $response = $this->post(route('exam-session.store', $exam), $data);

        $response->assertSessionHasErrors($field);
    }

    /** @test */
    public function exam_save_makes_entries_of_each_question_for_the_user() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $data = $this->getExamConfigurationFormData();

        $response = $this->post(route('exam-session.store', $exam), $data);

        $validateData = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
            'question_id' => $exam->questions[0]->id,
        ];

        $this->assertDatabaseHas('user_question', $validateData);
    }

    /** @test */
    public function exam_save_sets_list_of_questions() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $data = $this->getExamConfigurationFormData();
        $question_count = 3;
        $data['question_count'] = $question_count;

        $response = $this->post(route('exam-session.store', $exam), $data);

        $pivotRecord = \DB::table('exam_sessions')
            ->where('user_id', $user->id)
            ->where('set_id', $exam->id)
            ->first();

        $validateData = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
            'current_question' => 0,
        ];
        
        $this->assertDatabaseHas('exam_sessions', $validateData);
    
        // Assert that the pivot record exists
        $this->assertNotNull($pivotRecord);

        // Decode the JSON field
        $questionsArray = json_decode($pivotRecord->questions_array, true);

        // Assert that the questions_array has exactly 3 elements
        $this->assertIsArray($questionsArray);
        $this->assertCount($question_count, $questionsArray);
    }
            
    // DONE: Start the actual exam
    /** @test */
    public function redirected_to_test_page_after_saving_data() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $data = $this->getExamConfigurationFormData();

        $response = $this->post(route('exam-session.store', $exam), $data);

        $session = DB::table('exam_sessions')->where('set_id', $exam->id)->where('user_id', $user->id)->first();
        $response->assertRedirect(route('exam-session.test', $session->id));
    }

    /** @test */
    public function test_page_loads_appropriate_question() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        DB::table('exam_sessions')->insert([
            'user_id' => $user->id,
            'set_id' => $exam->id,
            'question_count' => 2,
            'questions_array' => '[7,4]',
            'current_question' => 1,
        ]);

        $response = $this->get(route('exam-session.test', $exam));

        $question = Question::find('4');
        $response->assertSee($question->text);
    }

    // If going to "start" while a test is in progress, go to test question

    // DONE: Validate that we see the current question number on the question page
    // DONE: Validate that we see the total number of questions on the question page
    /** @test */
    public function test_page_shows_correct_question_numbers() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);

        $response = $this->get(route('exam-session.test', $exam));

        $response->assertSeeInOrder(['Question', '#', '2', 'of',  '5', 'Select']);
    }

    /** @test */
    public function test_page_shows_all_answers_for_a_question() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $correctAnswer = $this->getQuestionAnswer($question, 1);
        $wrongAnswer = $this->getQuestionAnswer($question, 0);

        $response = $this->get(route('exam-session.test', $exam));

        $response->assertSee($correctAnswer->text);
        $response->assertSee($wrongAnswer->text);
    }

    /** @test */
    public function test_page_shows_answers_from_question_group() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $questionGroup = $this->CreateQuestionGroup(['set_id' => $exam->id, 'name' => 'Something']);
        $question1 = $this->CreateQuestion(['set_id' => $exam->id, 'group_id' => $questionGroup->id]);
        $question3 = $this->CreateQuestion(['set_id' => $exam->id, 'group_id' => $questionGroup->id]);
        $question4 = $this->CreateQuestion(['set_id' => $exam->id, 'group_id' => $questionGroup->id]);
        $question2 = $this->CreateQuestion(['set_id' => $exam->id, 'group_id' => $questionGroup->id]);
        $session->questions_array = json_encode([$question1->id, $question2->id]);
        DB::table('exam_sessions')->where('id', $session->id)->update(['questions_array' => $session->questions_array]);

        $response = $this->get(route('exam-session.test', $exam));

        $answer1 = $this->getQuestionAnswer($question1, 1);
        $answer2 = $this->getQuestionAnswer($question2, 1);
        $answer3 = $this->getQuestionAnswer($question3, 1);
        $answer4 = $this->getQuestionAnswer($question4, 1);

        $response->assertSee($answer1->text);
        $response->assertSee($answer2->text);
        $response->assertSee($answer3->text);
        $response->assertSee($answer4->text);
    }

    // TODO: Validate that the answer is correct
    public function answer_page_responds_for_correct_answer() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $correctAnswer = $this->getQuestionAnswer($question, 1);

        $data = [
            'answer-' . $correctAnswer->id => $correctAnswer->id,
            'question' => $question->id,
            'order' => '1,2',
        ];

        $response = $this->post(route('exam-session.answer', $exam), $data);

        $response->assertSee('Correct');
    }

    // TODO: Move the Question index to the next element on submit

    // TODO: When the last element has been reached, end the test

    // TODO: Finalize the ExamSession at the end of the test




    // TODO: Update the ExamSession when moving to a new question

    // TODO: Track Mastery Progress for this session after each question

    // TODO: Show a history of exam sessions that you have taken for an exam

    // TODO: Record a detail history of each question in a session, for paid users, so they can replay their exams later


    //** ========== HELPER FUNCTIONS ========== */
    public function getExamConfigurationFormData() {
        return [
            'question_count' => 1,
        ];
    }

    public function startExamSession($user, $exam) {
        DB::table('exam_sessions')->insert([
            'user_id' => $user->id,
            'set_id' => $exam->id,
            'question_count' => 5,
            'questions_array' => '[7,4]',
            'current_question' => 1,
        ]);

        $session = DB::table('exam_sessions')->where('user_id', $user->id)->where('set_id', $exam->id)->where('date_completed', null)->first();
        return $session;
    }

    public function getCurrentExamSessionQuestion($session) {
        $questionArray = json_decode($session->questions_array);
        $question = Question::find($questionArray[$session->current_question]);

        return $question;
    }

    public function getQuestionAnswer($question, $correct) {
        $answer = Answer::where('question_id', $question->id)->where('correct', $correct)->first();

        return $answer;
    }

    //** ========== DATA PROVIDERS ========== */
    public static function dataProviderForExamSessionStoreFormInvalidData()
    {
        return [
            ['question_count', ''],
            ['question_count', 'absdefa'],
            ['question_count', '-1'],
            ['question_count', '0'],
            ['question_count', '101'],
        ];
    }

    public static function dataProviderExamSessionPages() {
        /**
         * Route Name
         * Method == get or post
         * Expected Response Status
         * View Name
         */
        return [
            ['test', 'get', Response::HTTP_OK, 'test'],
            ['configure', 'get', Response::HTTP_OK, 'configure'],
            ['answer', 'post', Response::HTTP_OK, 'answer'],
        ];
    }
}
