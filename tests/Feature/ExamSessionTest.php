<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use App\Models\Question;
use App\Models\Answer;
use Carbon\Carbon;
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
        
        if ($route == 'summary') {
            $session = $this->completeExamSession($session);
            DB::table('exam_sessions')->where('id', $session->id)->update(['date_completed' => null]);
        }

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
            $view = 'exam-session.' . $view;
            $response->assertViewIs($view);
        }
    }

    // DONE: Create an ExamSession when a user starts a new instance of a test
    /** @test */
    public function exam_session_created_when_first_taking_an_exam() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $data = $this->getExamConfigurationFormData();

        $response = $this->post(route('exam-session.store', $exam), $data);

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
        $data = $this->getExamConfigurationFormData();

        $preTestData = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ];
        DB::table('exam_sessions')->insert($preTestData);

        $response = $this->post(route('exam-session.store', $exam), $data);

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
        $session = $this->startExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);

        $response = $this->get(route('exam-session.test', $exam));

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
        $questionNumber = $session->current_question + 1;
        $totalQuestions = $session->question_count;

        $response = $this->get(route('exam-session.test', $exam));

        $response->assertSeeInOrder(['Question', '#', $questionNumber, 'of',  $totalQuestions, 'Select']);
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

    // DONE: Validate that the answer is correct
    /** @test */
    public function answer_page_responds_for_correct_answer() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $incorrectAnswer = $this->getQuestionAnswer($question, 0);
        $correctAnswer = $this->getQuestionAnswer($question, 1);

        $data = [
            'answer' => $correctAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$incorrectAnswer->id, $correctAnswer->id]),
        ];

        $response = $this->post(route('exam-session.answer', $exam), $data);

        $response->assertSeeInOrder([$question->text, 'text-success', 'Correct', 'Your Answer']);
    }

    /** @test */
    public function answer_page_responds_for_incorrect_answer() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $incorrectAnswer = $this->getQuestionAnswer($question, 0);
        $correctAnswer = $this->getQuestionAnswer($question, 1);

        $data = [
            'answer' => $incorrectAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$incorrectAnswer->id, $correctAnswer->id]),
        ];

        $response = $this->post(route('exam-session.answer', $exam), $data);

        $response->assertSeeInOrder([$question->text, 'text-error', 'Incorrect', 'Your Answer']);
    }
    
    // DONE: Move the Question index to the next element on submit
    /** @test */
    public function the_session_index_is_moved_after_question_is_answered() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $incorrectAnswer = $this->getQuestionAnswer($question, 0);
        $correctAnswer = $this->getQuestionAnswer($question, 1);
        $currentCount = $session->current_question;

        $data = [
            'answer' => $correctAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$incorrectAnswer->id, $correctAnswer->id]),
        ];

        $response = $this->post(route('exam-session.answer', $exam), $data);

        $verifyData = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
            'current_question' => ($currentCount + 1)
        ];
        
        $this->assertDatabaseHas('exam_sessions', $verifyData);
    }

    /** @test */
    public function answer_page_increments_session_correct_answer_count() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $incorrectAnswer = $this->getQuestionAnswer($question, 0);
        $correctAnswer = $this->getQuestionAnswer($question, 1);
        $correctAnswerCount = $session->correct_answers;
        $incorrectAnswerCount = $session->incorrect_answers;

        $data = [
            'answer' => $correctAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$incorrectAnswer->id, $correctAnswer->id]),
        ];

        $response = $this->post(route('exam-session.answer', $exam), $data);

        $verifyData = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
            'correct_answers' => ($correctAnswerCount + 1),
            'incorrect_answers' => ($incorrectAnswerCount),
        ];
        
        $this->assertDatabaseHas('exam_sessions', $verifyData);
    }

    /** @test */
    public function answer_page_increments_session_incorrect_answer_count() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $incorrectAnswer = $this->getQuestionAnswer($question, 0);
        $correctAnswer = $this->getQuestionAnswer($question, 1);
        $correctAnswerCount = $session->correct_answers;
        $incorrectAnswerCount = $session->incorrect_answers;

        $data = [
            'answer' => $incorrectAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$incorrectAnswer->id, $correctAnswer->id]),
        ];

        $response = $this->post(route('exam-session.answer', $exam), $data);

        $verifyData = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
            'incorrect_answers' => ($incorrectAnswerCount + 1),
            'correct_answers' => ($correctAnswerCount),
        ];
        
        $this->assertDatabaseHas('exam_sessions', $verifyData);
    }

    // DONE: Update the mastery level of the questions
    /** @test */
    public function answering_questions_correctly_updates_question_mastery() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $correctAnswer = $this->getQuestionAnswer($question, 1);
        Config::set('add_score', 1);

        $submitData = [
            'answer' => $correctAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$correctAnswer->id]),
        ];

        $verifyData = [
            'user_id' => $user->id,
            'question_id' => $question->id,
            'set_id' => $exam->id,
            'score' => 5,
        ];

        DB::table('user_question')->where('user_id', $user->id)->where('question_id', $question->id)->update($verifyData);

        $response = $this->post(route('exam-session.answer', $exam), $submitData);

        $verifyData['score'] = $verifyData['score'] + config('add_score');
        unset($verifyData['next_at']);
        $this->assertDatabaseHas('user_question', $verifyData);
    }

    /** @test */
    public function answering_questions_incorrectly_updates_question_mastery() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $incorrectAnswer = $this->getQuestionAnswer($question, 0);
        Config::set('test.sub_score', 1);

        $submitData = [
            'answer' => $incorrectAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$incorrectAnswer->id]),
        ];

        $verifyData = [
            'user_id' => $user->id,
            'question_id' => $question->id,
            'set_id' => $exam->id,
            'score' => 5,
        ];

        DB::table('user_question')->where('user_id', $user->id)->where('question_id', $question->id)->update($verifyData);

        $response = $this->post(route('exam-session.answer', $exam), $submitData);

        $verifyData['score'] = $verifyData['score'] - config('test.sub_score');
        $this->assertDatabaseHas('user_question', $verifyData);
    }
    
    /** @test */
    public function answering_questions_incorrectly_keeps_mastery_at_a_minimum() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $incorrectAnswer = $this->getQuestionAnswer($question, 0);
        Config::set('test.sub_score', 5);
        Config::set('test.min_score', 2);

        $submitData = [
            'answer' => $incorrectAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$incorrectAnswer->id]),
        ];

        $verifyData = [
            'question_id' => $question->id,
            'score' => 3,
        ];

        DB::table('user_question')->where('user_id', $user->id)->where('question_id', $question->id)->update($verifyData);

        $response = $this->post(route('exam-session.answer', $exam), $submitData);

        $verifyData['score'] = config('test.min_score');
        $this->assertDatabaseHas('user_question', $verifyData);
    }
    
    // DONE: When the last element has been reached, end the test
    /** @test */
    public function test_page_goes_to_summary_if_the_test_is_over() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $updateSession['current_question'] = ($session->question_count - 1);
        $updateSession['correct_answers'] = $session->question_count;
        DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);

        $response = $this->get(route('exam-session.test', $exam));

        $response->assertRedirect(route('exam-session.summary', $exam));
    }

    /** @test */
    public function session_end_time_is_set_when_test_is_complete() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);
        $updateSession['current_question'] = ($session->question_count - 1);
        $updateSession['correct_answers'] = $session->question_count;
        DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);

        $response = $this->get(route('exam-session.summary', $exam));

        $updatedSession = DB::table('exam_sessions')->where('id', $session->id)->first();
        $this->assertNotNull($updatedSession->date_completed);
    }

    /** @test */
    public function going_to_the_summary_page_during_a_test_redirects_to_the_test() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->startExamSession($user, $exam);

        $response = $this->get(route('exam-session.summary', $exam));

        $response->assertRedirect(route('exam-session.test', $exam));
    }
    
    // TODO: Finalize the ExamSession at the end of the test
    
    // TODO: Display the grade and number of right and wrong answers
    
    // TODO: The start page redirects to the test if it's already in progress



   // TODO: Show a history of exam sessions that you have taken for an exam (Basic results (grade only) for free accounts)

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
            'questions_array' => '[7,4,2,5,1]',
            'current_question' => 0,
            'correct_answers' => 0,
            'incorrect_answers' => 0,
        ]);

        $session = DB::table('exam_sessions')->where('user_id', $user->id)->where('set_id', $exam->id)->where('date_completed', null)->first();

        $questions = Question::where('set_id', $exam->id)->get();
        foreach ($questions as $question) {
            DB::table('user_question')->insert([
                'user_id' => $user->id,
                'set_id' => $exam->id,
                'question_id' => $question->id,
                'score' => 2,
                'next_at' => Carbon::now(),
            ]);
        }

        return $session;
    }

    public function completeExamSession($session) {
        $updateSession['current_question'] = $session->question_count - 1;
        $updateSession['correct_answers'] = ceil($session->question_count / 2);
        $updateSession['incorrect_answers'] = floor($session->question_count / 2);
        $updateSession['date_completed'] = Carbon::now();

        DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);
        $session = DB::table('exam_sessions')->where('id', $session->id)->first();

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
            ['summary', 'get', Response::HTTP_OK, 'summary'],
        ];
    }
}
