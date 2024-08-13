<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Models\Answer;
use App\Models\Question;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PracticeControllerTest extends TestCase
{
    #[Test]
    public function default_page_has_link_to_practice(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);

        $response = $this->get(route('profile.exams'));

        $response->assertSee(route('practice.start', $exam));
    }

    // Going to the start page redirects to a configuration page if there is no configuration saved for this ExamSet
    /** DISABLED */
    public function practice_start_redirects_to_practice_config_if_no_db_data()
    {
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

    #[Test]
    public function practice_start_page_creates_practice_session_record(): void
    {
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

    #[Test]
    public function practice_start_page_redirects_to_review_page(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);

        $response = $this->get(route('practice.start', $exam));

        $response->assertRedirect(route('practice.review', $exam));
    }

    #[Test]
    public function practice_review_page_loads(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);

        $response = $this->get(route('practice.review', $exam));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('practice.review');
    }

    #[Test]
    public function practice_review_page_loads_question_data(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);
        $question = Question::find(1);

        $response = $this->get(route('practice.review', $exam));

        $response->assertSee($exam->name);
        $response->assertSee($question->text);
    }

    #[Test]
    public function practice_review_page_loads_answer_data(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);
        $question = Question::find(1);
        $answer = Answer::where('question_id', 1)->where('correct', 1)->first();

        $response = $this->get(route('practice.review', $exam));

        $response->assertSee($answer->text);
    }

    #[Test]
    public function practice_next_page_increases_index(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);

        $response = $this->get(route('practice.next', $exam));

        $verifyData = [
            'question_index' => $session->question_index + 1,
        ];

        $this->assertDatabaseHas('exam_practices', $verifyData);
    }

    #[Test]
    public function practice_next_page_redirects_to_review_page(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);

        $response = $this->get(route('practice.next', $exam));

        $response->assertRedirect(route('practice.review', $exam));
    }

    #[Test]
    public function practice_start_page_redirects_to_review_if_in_session(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);

        $response = $this->get(route('practice.start', $exam));

        $this->assertDatabaseCount('exam_practices', 1);
        $response->assertRedirect(route('practice.review', $exam));
    }

    #[Test]
    public function practice_next_page_ends_session_when_last_question_was_reached(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);
        $session->update([
            'question_index' => $session->question_count - 1,
        ]);

        $response = $this->get(route('practice.next', $exam));

        $response->assertRedirect(route('practice.done', $exam));
    }

    #[Test]
    public function practice_done_page_loads(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);

        $response = $this->get(route('practice.done', $exam));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('practice.done');
    }

    #[Test]
    public function practice_next_page_redirects_to_done_if_last_question_was_readched(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);
        $session->update([
            'question_index' => $session->question_count,
        ]);

        $response = $this->get(route('practice.review', $exam));

        $response->assertRedirect(route('practice.done', $exam));
    }

    #[Test]
    public function practice_done_page_destroys_session(): void
    {
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

    #[Test]
    public function practice_previous_page_redirects_to_review_page(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $user->id]);
        $session = $this->StartPracticeSession($user, $exam);

        $response = $this->get(route('practice.previous', $exam));

        $response->assertRedirect(route('practice.review', $exam));
    }

    #[Test]
    public function practice_previous_page_redirects_to_review_if_zero_reached(): void
    {
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
