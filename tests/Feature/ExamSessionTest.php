<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ExamSessionTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider dataProviderExamSessionPages
     */
    public function validate_that_pages_load_correctly($route, $method, $status, $view): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $question = $this->CreateQuestion(['set_id' => $exam->id]);
        DB::table('user_question')->insert([
            'user_id' => $user->id,
            'set_id' => $exam->id,
            'question_id' => $question->id,
            'score' => 0,
            'next_at' => Carbon::now()->subDays(1),
        ]);
        $data = [];

        if ($route == 'summary') {
            $session = $this->CompleteExamSession($session);
            DB::table('exam_sessions')->where('id', $session->id)->update(['date_completed' => null]);
        }

        if ($route == 'answer') {
            $data = [
                'answer-1' => 1,
                'question' => 1,
                'order' => '[1]',
            ];
        }

        $route = 'exam-session.'.$route;

        if ($method == 'get') {
            $response = $this->get(route($route, $exam));
        } else {
            $response = $this->post(route($route, $exam), $data);
        }

        $response->assertStatus($status);

        if ($status == Response::HTTP_OK) {
            $view = 'exam-session.'.$view;
            $response->assertViewIs($view);
        }
    }

    // DONE: Create an ExamSession when a user starts a new instance of a test
    /** @test */
    public function exam_session_created_when_first_taking_an_exam(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $data = $this->getExamConfigurationFormData();
        $this->RegisterUserQuestions($user, $exam);

        $response = $this->post(route('exam-session.store', $exam), $data);

        $data = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ];

        $this->assertDatabaseHas('exam_sessions', $data);
    }

    /** @test */
    public function exam_session_is_not_created_when_an_exam_is_already_in_progress(): void
    {
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
    public function redirected_to_exam_configuration_page_when_starting_a_new_exam_session(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        DB::table('exam_records')->insert([
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ]);

        $response = $this->get(route('exam-session.start', $exam));

        $response->assertRedirect(route('exam-session.configure', $exam));
    }

    /** @test */
    public function exam_configuration_page_not_allowed_for_private_exams(): void
    {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 0]);

        $response = $this->get(route('exam-session.configure', $exam));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function exam_configuration_page_is_allowed_for_public_exams(): void
    {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 1]);

        $response = $this->get(route('exam-session.configure', $exam));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function exam_configuration_page_loads_exam_set_data(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $response = $this->get(route('exam-session.configure', $exam));

        $response->assertSee($exam->name);
    }

    /** @test */
    public function exam_start_page_redirects_to_test_if_session_already_exists(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);

        $response = $this->get(route('exam-session.start', $exam));

        $response->assertRedirect(route('exam-session.test', $exam));
    }

    // DONE: Store configuration for this exam
    /** @test */
    public function exam_configuration_saves_to_database(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $this->RegisterUserQuestions($user, $exam);

        $data = [
            'question_count' => 1,
        ];

        $response = $this->post(route('exam-session.store', $exam), $data);

        $this->assertDatabaseHas('exam_sessions', $data);
    }

    /** @test */
    public function exam_save_page_not_allowed_for_private_exams(): void
    {
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
    public function exam_save_page_is_allowed_for_public_exams(): void
    {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 1]);
        $this->RegisterUserQuestions($user, $exam);

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
     *
     * @dataProvider dataProviderForExamSessionStoreFormInvalidData
     * */
    public function exam_save_validates_data($field, $value): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $data = $this->getExamConfigurationFormData();
        $data[$field] = $value;

        $response = $this->post(route('exam-session.store', $exam), $data);

        $response->assertSessionHasErrors($field);
    }

    /** @test */
    public function exam_save_makes_entries_of_each_question_for_the_user(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $data = $this->getExamConfigurationFormData();
        $this->RegisterUserQuestions($user, $exam);

        $response = $this->post(route('exam-session.store', $exam), $data);

        $validateData = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
            'question_id' => $exam->questions[0]->id,
        ];

        $this->assertDatabaseHas('user_question', $validateData);
    }

    /** @test */
    public function exam_save_sets_list_of_questions(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $data = $this->getExamConfigurationFormData();
        $this->RegisterUserQuestions($user, $exam);
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
    public function redirected_to_test_page_after_saving_data(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $data = $this->getExamConfigurationFormData();
        $this->RegisterUserQuestions($user, $exam);

        $response = $this->post(route('exam-session.store', $exam), $data);

        $session = DB::table('exam_sessions')->where('set_id', $exam->id)->where('user_id', $user->id)->first();
        $response->assertRedirect(route('exam-session.test', $session->id));
    }

    /** @test */
    public function test_page_loads_appropriate_question(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);

        $response = $this->get(route('exam-session.test', $exam));

        $response->assertSee($question->text);
    }

    // If going to "start" while a test is in progress, go to test question

    // DONE: Validate that we see the current question number on the question page
    // DONE: Validate that we see the total number of questions on the question page
    /** @test */
    public function test_page_shows_correct_question_numbers(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $questionNumber = $session->current_question + 1;
        $totalQuestions = $session->question_count;

        $response = $this->get(route('exam-session.test', $exam));

        $response->assertSeeInOrder(['Question', '#', $questionNumber, 'of',  $totalQuestions, 'Select']);
    }

    /** @test */
    public function test_page_shows_all_answers_for_a_question(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $correctAnswer = $this->getQuestionAnswer($question, 1);
        $wrongAnswer = $this->getQuestionAnswer($question, 0);

        $response = $this->get(route('exam-session.test', $exam));

        $response->assertSee($correctAnswer->text);
        $response->assertSee($wrongAnswer->text);
    }

    /** @test */
    public function test_page_shows_answers_from_question_group(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
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
    public function answer_page_responds_for_correct_answer(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $incorrectAnswer = $this->getQuestionAnswer($question, 0);
        $correctAnswer = $this->getQuestionAnswer($question, 1);

        $data = [
            'answer' => $correctAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$incorrectAnswer->id, $correctAnswer->id]),
        ];

        $response = $this->post(route('exam-session.answer', $exam), $data);

        $response->assertSeeInOrder([$question->text, 'Correct', 'Your Answer']);
    }

    /** @test */
    public function answer_page_responds_for_incorrect_answer(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $incorrectAnswer = $this->getQuestionAnswer($question, 0);
        $correctAnswer = $this->getQuestionAnswer($question, 1);

        $data = [
            'answer' => $incorrectAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$incorrectAnswer->id, $correctAnswer->id]),
        ];

        $response = $this->post(route('exam-session.answer', $exam), $data);

        $response->assertSeeInOrder([$question->text, 'Incorrect', 'Your Answer']);
    }

    // DONE: Move the Question index to the next element on submit
    /** @test */
    public function the_session_index_is_moved_after_question_is_answered(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
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
            'current_question' => ($currentCount + 1),
        ];

        $this->assertDatabaseHas('exam_sessions', $verifyData);
    }

    /** @test */
    public function answer_page_increments_session_correct_answer_count(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
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
    public function answer_page_increments_session_incorrect_answer_count(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
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
    public function answering_questions_correctly_updates_question_mastery(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
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
    public function answering_questions_incorrectly_updates_question_mastery(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
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
    public function answering_questions_incorrectly_keeps_mastery_at_a_minimum(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
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
    public function test_page_goes_to_summary_if_the_test_is_over(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $updateSession['current_question'] = ($session->question_count);
        $updateSession['correct_answers'] = $session->question_count;
        DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);

        $response = $this->get(route('exam-session.test', $exam));

        $response->assertRedirect(route('exam-session.summary', $exam));
    }

    /** @test */
    public function session_end_time_is_set_when_test_is_complete(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $updateSession['current_question'] = ($session->question_count);
        $updateSession['correct_answers'] = $session->question_count;
        DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);

        $response = $this->get(route('exam-session.summary', $exam));

        $updatedSession = DB::table('exam_sessions')->where('id', $session->id)->first();
        $this->assertNotNull($updatedSession->date_completed);
    }

    /** @test */
    public function going_to_the_summary_page_during_a_test_redirects_to_the_test(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);

        $response = $this->get(route('exam-session.summary', $exam));

        $response->assertRedirect(route('exam-session.test', $exam));
    }

    /**
     * @test
     *
     * @dataProvider dataProviderSessionScoreCalculations
     * */
    public function summary_calculates_the_score($numCorrect, $numIncorrect, $grade): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $updateSession['correct_answers'] = $numCorrect;
        $updateSession['incorrect_answers'] = $numIncorrect;
        $updateSession['question_count'] = $numCorrect + $numIncorrect;
        $updateSession['current_question'] = $updateSession['question_count'];
        DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);

        $response = $this->get(route('exam-session.summary', $exam));

        $validateData = [
            'id' => $session->id,
            'grade' => $grade,
        ];

        $this->assertDatabaseHas('exam_sessions', $validateData);
    }

    // DONE: Finalize the ExamSession at the end of the test

    // DONE: Going to the summary page loads the latest test session summary
    /** @test */
    public function summary_page_loads_latest_completed_test_if_no_active_test_sessions(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $session = $this->CompleteExamSession($session);

        $response = $this->get(route('exam-session.summary', $exam));

        $response->assertStatus(Response::HTTP_OK);
    }

    // DONE: Display the grade and number of right and wrong answers
    /** @test */
    public function summary_page_shows_result_data(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $session = $this->CompleteExamSession($session);

        $response = $this->get(route('exam-session.summary', $exam));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($session->grade);
        $response->assertSeeInOrder(['Correct', $session->correct_answers]);
        $response->assertSeeInOrder(['Incorrect', $session->incorrect_answers]);
    }

    // DONE: Track the mastery status increases for this exam session
    /**
     * @test
     *
     * @dataProvider dataProviderMasteryUpdate
     * */
    public function mastery_status_is_tracked_for_correct_answers($masteryLevel): void
    {
        Config::set('test.grade_apprentice', 1);
        Config::set('test.grade_familiar', 1);
        Config::set('test.grade_proficient', 1);
        Config::set('test.grade_mastered', 1);
        Config::set('test.add_score', 1);
        Config::set('test.grade_'.$masteryLevel, 5);

        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $correctAnswer = $this->getQuestionAnswer($question, 1); // Get the correct answer for this question

        $updateData['score'] = 4; // Set this to one below the required mastery level
        DB::table('user_question')->where('user_id', $user->id)->where('question_id', $question->id)->update($updateData);

        $submitData = [
            'answer' => $correctAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$correctAnswer->id]),
        ];
        $response = $this->post(route('exam-session.answer', $exam), $submitData);

        $verifyData = [
            'id' => $session->id,
            'mastery_apprentice_change' => 0,
            'mastery_familiar_change' => 0,
            'mastery_proficient_change' => 0,
            'mastery_mastered_change' => 0,
        ];
        $verifyData['mastery_'.$masteryLevel.'_change'] = 1;
        $this->assertDatabaseHas('exam_sessions', $verifyData);
    }

    // DONE: Prevent answer page from updating info if the question was already answered
    /** @test */
    public function answer_page_does_not_double_count_answer(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $correctAnswer = $this->getQuestionAnswer($question, 1);

        $newQuestionNumber = $session->current_question + 1;
        $numCorrect = $session->correct_answers + 1;
        $updateData = [
            'current_question' => $newQuestionNumber,
            'correct_answers' => $numCorrect,
        ];

        DB::table('exam_sessions')->where('id', $session->id)->update($updateData);

        $submitData = [
            'answer' => $correctAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$correctAnswer->id]),
        ];

        $response = $this->post(route('exam-session.answer', $exam), $submitData);

        $verifyData = [
            'id' => $session->id,
            'correct_answers' => $numCorrect,
            'current_question' => $newQuestionNumber,
        ];
        $this->assertDatabaseHas('exam_sessions', $verifyData);
    }

    // DONE: Show the mastery satus increase on the answer page
    /** @test */
    public function mastery_increase_shows_on_answer_page(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $correctAnswer = $this->getQuestionAnswer($question, 1); // Get the correct answer for this question

        $updateData['score'] = config('test.grade_familiar');
        DB::table('user_question')->where('user_id', $user->id)->where('question_id', $question->id)->update($updateData);

        $submitData = [
            'answer' => $correctAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$correctAnswer->id]),
        ];
        $response = $this->post(route('exam-session.answer', $exam), $submitData);

        $response->assertSee('Mastery: + '.config('test.add_score'));
    }

    /** @test */
    public function mastery_decrease_shows_on_answer_page(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $question = $this->getCurrentExamSessionQuestion($session);
        $incorrectAnswer = $this->getQuestionAnswer($question, 0); // Get the correct answer for this question

        $updateData['score'] = config('test.grade_familiar');
        DB::table('user_question')->where('user_id', $user->id)->where('question_id', $question->id)->update($updateData);

        $submitData = [
            'answer' => $incorrectAnswer->id,
            'question' => $question->id,
            'order' => json_encode([$incorrectAnswer->id]),
        ];
        $response = $this->post(route('exam-session.answer', $exam), $submitData);

        $response->assertSee('Mastery: - '.config('test.sub_score'));
    }

    // TODO: Show the mastery status increase count on the summary page
    // Maybe show a +1 / -1 next to the level if a level up/down did not happen
    // Show a badge if a level up happened
    // Show a badge if a level down happened

    // DONE: Accessing the answer page with a GET request should redirect to the test page
    /** @test */
    public function answer_get_request_redirects_to_test_page(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);

        $response = $this->get(route('exam-session.answer', $exam));

        $response->assertRedirect(route('exam-session.test', $exam));
    }

    // TODO: Write a test for checking that tests start correctly if a completed test already exists

    // TODO: Show a history of exam sessions that you have taken for an exam (Basic results (grade only) for free accounts)

    // TODO: Record a detail history of each question in a session, for paid users, so they can replay their exams later

    //** ========== HELPER FUNCTIONS ========== */
    public function getExamConfigurationFormData()
    {
        return [
            'question_count' => 1,
        ];
    }

    public function getCurrentExamSessionQuestion($session)
    {
        $questionArray = json_decode($session->questions_array);
        $question = Question::find($questionArray[$session->current_question]);

        return $question;
    }

    public function getQuestionAnswer($question, $correct)
    {
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

    public static function dataProviderExamSessionPages()
    {
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

    public static function dataProviderSessionScoreCalculations()
    {
        /**
         * # Right
         * # Wrong
         * Expected score as integer
         */
        return [
            [10, 0, 100],
            [0, 10, 0],
            [5, 5, 50],
            [7, 3, 70],
            [7, 2, 78], // Round Up 77.77
            [2, 4, 33], // Round Down 33.33
            [6, 5, 55], // Round up at the border 54.54
        ];
    }

    public static function dataProviderMasteryUpdate()
    {
        /**
         * Mastery Level
         */
        return [
            ['mastered'],
            ['proficient'],
            ['familiar'],
            ['apprentice'],
        ];
    }
}
