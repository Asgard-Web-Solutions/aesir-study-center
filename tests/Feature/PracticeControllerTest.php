<?php

namespace Tests\Feature;

use App\Models\Answer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Pennant\Feature;
use App\Models\Question;
use Tests\TestCase;

class PracticeControllerTest extends TestCase
{
    /** @test */
    public function default_page_has_link_to_practice() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);

        $response = $this->get(route('profile.exams'));

        $response->assertSee(route('practice.start', $exam));
    }

    // Going to the start page redirects to a configuration page if there is no configuration saved for this ExamSet
    /** DISABLED */
    public function practice_start_redirects_to_practice_config_if_no_db_data() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $response = $this->get(route('practice.start', $exam));

        $response->assertRedirect(route('practice.config', $exam));
    }

    /** DISABLED */
    public function practice_config_page_loads()
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);

        $response = $this->get(route('practice.config', $exam));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('practice.config');
        $response->assertSee(route('practice.begin', $exam));
    }

    /** @test */
    public function practice_start_page_creates_practice_session_record() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);

        $response = $this->get(route('practice.start', $exam));

        $verifyData = [
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'question_count' => $exam->questions->count(),
            'question_index' => 0,
        ];
        
        $this->assertDatabaseHas('exam_practices', $verifyData);
    }

    /** @test */
    public function practice_start_page_redirects_to_review_page() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);

        $response = $this->get(route('practice.start', $exam));

        $response->assertRedirect(route('practice.review', $exam));
    }

    /** @test */
    public function practice_review_page_loads() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);

        $response = $this->get(route('practice.review', $exam));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('practice.review');
    }

    /** @test */
    public function practice_review_page_loads_question_data() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);
        $question = Question::find(1);

        $response = $this->get(route('practice.review', $exam));

        $response->assertSee($exam->name);
        $response->assertSee($question->text);
    }

    /** @test */
    public function practice_review_page_loads_answer_data() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);
        $question = Question::find(1);
        $answer = Answer::where('question_id', 1)->where('correct', 1)->first();

        $response = $this->get(route('practice.review', $exam));

        $response->assertSee($answer->text);
    }

    /** @test */
    public function practice_next_page_increases_index() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);

        $response = $this->get(route('practice.next', $exam));

        $verifyData = [
            'question_index' => $session->question_index + 1,
        ];

        $this->assertDatabaseHas('exam_practices', $verifyData);
    }

    /** @test */
    public function practice_next_page_redirects_to_review_page() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);

        $response = $this->get(route('practice.next', $exam));

        $response->assertRedirect(route('practice.review', $exam));
    }

    /** @test */
    public function practice_start_page_redirects_to_review_if_in_session() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);

        $response = $this->get(route('practice.start', $exam));

        $this->assertDatabaseCount('exam_practices', 1);
        $response->assertRedirect(route('practice.review', $exam));
    }

    /** @test */
    public function practice_next_page_ends_session_when_last_question_was_reached() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);
        $session->update([
            'question_index' => $session->question_count - 1,
        ]);

        $response = $this->get(route('practice.next', $exam));

        $response->assertRedirect(route('practice.done', $exam));
    }

    /** @test */
    public function practice_done_page_loads() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);

        $response = $this->get(route('practice.done', $exam));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('practice.done');
    }

    /** @test */
    public function practice_next_page_redirects_to_done_if_last_question_was_readched() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);
        $session->update([
            'question_index' => $session->question_count,
        ]);

        $response = $this->get(route('practice.review', $exam));

        $response->assertRedirect(route('practice.done', $exam));
    }

    /** @test */
    public function practice_done_page_destroys_session() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);
        $session->update([
            'question_index' => $session->question_count,
        ]);

        $response = $this->get(route('practice.done', $exam));

        $verifyData = [
            'id' => $session->id,
        ];

        $this->assertDatabaseMissing('exam_practices', $verifyData);
    }

    /** @test */
    public function practice_previous_page_redirects_to_review_page() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);

        $response = $this->get(route('practice.previous', $exam));

        $response->assertRedirect(route('practice.review', $exam));
    }

    /** @test */
    public function practice_previous_page_redirects_to_review_if_zero_reached() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);
        $session->update([
            'question_index' => 0,
        ]);

        $response = $this->get(route('practice.previous', $exam));

        $response->assertRedirect(route('practice.review', $exam));
    }


    
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
