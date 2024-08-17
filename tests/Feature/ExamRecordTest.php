<?php

namespace Tests\Feature;

use App\Models\Question;
use Carbon\Carbon;
use Config;
use DB;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ExamRecordTest extends TestCase
{
    // DONE: When a user takes an exam for the first time, create the exam record
    /** @test */
    public function exam_record_created_when_first_taking_an_exam(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $response = $this->get(route('exam-session.enroll', $exam));

        $data = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ];

        $this->assertDatabaseHas('exam_records', $data);
    }

    /** @test */
    public function exam_record_is_not_duplicated_if_already_exists(): void
    {
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
    public function user_can_start_their_own_private_exam(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $response = $this->get(route('exam-session.start', $exam));

        $response->assertRedirect(route('exam-session.register', $exam));
    }

    /** @test */
    public function other_users_cannot_start_private_exams(): void
    {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 0]);

        $response = $this->get(route('exam-session.start', $exam));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function other_users_can_start_public_exams(): void
    {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 1]);

        $response = $this->get(route('exam-session.start', $exam));

        $response->assertRedirect(route('exam-session.register', $exam));
    }

    // DONE: When a user completes an exam, update the exam record stats (latest grade, times taken, last completed)
    /** @test */
    public function exam_updates_times_taken_after_completing_exam(): void
    {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 1]);
        $session = $this->StartExamSession($user, $exam);
        $session = $this->CompleteExamSession($session);
        DB::table('exam_sessions')->where('id', $session->id)->update(['date_completed' => null]);

        $response = $this->get(route('exam-session.summary', $exam));

        $verifyData = [
            'set_id' => $exam->id,
            'times_taken' => 1,
        ];
        $this->assertDatabaseHas('exam_records', $verifyData);
    }

    /** @test */
    public function a_completed_exam_is_not_counted_twice_when_viewing_the_summary_page(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $session = $this->CompleteExamSession($session);
        DB::table('exam_sessions')->where('id', $session->id)->update(['date_completed' => null]);

        $response = $this->get(route('exam-session.summary', $exam));
        $response = $this->get(route('exam-session.summary', $exam));

        $verifyData = [
            'set_id' => $exam->id,
            'times_taken' => 1,
        ];
        $this->assertDatabaseHas('exam_records', $verifyData);
    }

    /** @test */
    public function completing_an_exam_averages_the_latest_session_grades(): void
    {
        Config::set('count_tests_for_average_score', 5);
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $session = $this->CompleteExamSession($session);
        DB::table('exam_sessions')->where('id', $session->id)->update([
            'date_completed' => null,
            'correct_answers' => $session->question_count,
            'current_question' => $session->question_count,
        ]);

        // Generate test data to calculate averages with
        for ($i = 1; $i <= 4; $i++) {
            DB::table('exam_sessions')->insert([
                'user_id' => $user->id,
                'set_id' => $exam->id,
                'question_count' => 2,
                'current_question' => 2,
                'correct_answers' => 1,
                'incorrect_answers' => 1,
                'grade' => 50,
                'date_completed' => Carbon::now(),
            ]);
        }

        // Throw in an older test with a zero score so we know that this is using the latest scores to calculate the average
        DB::table('exam_sessions')->insert([
            'user_id' => $user->id,
            'set_id' => $exam->id,
            'question_count' => 2,
            'current_question' => 2,
            'correct_answers' => 0,
            'incorrect_answers' => 2,
            'grade' => 0,
            'date_completed' => '1970-01-01 01:01:01',
        ]);

        $response = $this->get(route('exam-session.summary', $exam));

        // 4 50s and a 100 = 300, which averages to 60%
        $verifyData = [
            'set_id' => $exam->id,
            'recent_average' => 60,
        ];
        $this->assertDatabaseHas('exam_records', $verifyData);
    }

    /** @test */
    public function exam_average_score_is_stored_as_an_integer(): void
    {
        Config::set('count_tests_for_average_score', 5);
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $session = $this->CompleteExamSession($session);
        DB::table('exam_sessions')->where('id', $session->id)->update([
            'date_completed' => null,
            'correct_answers' => $session->question_count,
            'incorrect_answers' => 0,
            'current_question' => $session->question_count,
        ]);

        // Generate test data to calculate averages with
        for ($i = 1; $i <= 2; $i++) {
            DB::table('exam_sessions')->insert([
                'user_id' => $user->id,
                'set_id' => $exam->id,
                'question_count' => 2,
                'current_question' => 2,
                'correct_answers' => 1,
                'incorrect_answers' => 1,
                'grade' => 30,
                'date_completed' => Carbon::now(),
            ]);
        }

        $response = $this->get(route('exam-session.summary', $exam));

        $verifyData = [
            'set_id' => $exam->id,
            'recent_average' => 53,
        ];
        $this->assertDatabaseHas('exam_records', $verifyData);
    }

    /** @test */
    public function exam_record_has_last_taken_set_after_completing_a_session(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $session = $this->CompleteExamSession($session);
        DB::table('exam_sessions')->where('id', $session->id)->update(['date_completed' => null]);

        $response = $this->get(route('exam-session.summary', $exam));

        $record = DB::table('exam_records')->where('set_id', $exam->id)->where('user_id', $user->id)->first();
        $this->assertNotNull($record->last_completed);
    }

    // DONE: Completing an exam updates the mastery progress of the record
    /** @test */
    public function exam_record_gets_mastery_progress_calculation(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $session = $this->CompleteExamSession($session);
        DB::table('exam_sessions')->where('id', $session->id)->update(['date_completed' => null]);
        $questions = Question::where('set_id', $exam->id)->get();

        // 10 questions are generated in the CreateSet() method.
        $this->setQuestionMasteryLevel($user, $questions[0], config('test.grade_mastered'));
        $this->setQuestionMasteryLevel($user, $questions[1], config('test.grade_proficient'));
        $this->setQuestionMasteryLevel($user, $questions[2], config('test.grade_proficient'));
        $this->setQuestionMasteryLevel($user, $questions[3], config('test.grade_familiar'));
        $this->setQuestionMasteryLevel($user, $questions[4], config('test.grade_familiar'));
        $this->setQuestionMasteryLevel($user, $questions[5], config('test.grade_familiar'));
        $this->setQuestionMasteryLevel($user, $questions[6], config('test.grade_apprentice'));
        $this->setQuestionMasteryLevel($user, $questions[7], config('test.grade_apprentice'));
        $this->setQuestionMasteryLevel($user, $questions[8], config('test.grade_apprentice'));
        $this->setQuestionMasteryLevel($user, $questions[9], config('test.grade_apprentice'));

        $response = $this->get(route('exam-session.summary', $exam));

        $verifyData = [
            'set_id' => $exam->id,
            'user_id' => $user->id,
            'mastery_mastered_count' => 1,
            'mastery_proficient_count' => 3,
            'mastery_familiar_count' => 6,
            'mastery_apprentice_count' => 10,
        ];
        $this->assertDatabaseHas('exam_records', $verifyData);
    }

    // DONE: Show the mastery level on the summary page
    /** @test */
    public function summary_page_shows_the_overall_mastery_progress(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);
        $session = $this->CompleteExamSession($session);
        DB::table('exam_records')->where('user_id', $user->id)->where('set_id', $exam->id)->update([
            'mastery_mastered_count' => 1,
            'mastery_proficient_count' => 3,
            'mastery_familiar_count' => 6,
            'mastery_apprentice_count' => 10,
        ]);

        $response = $this->get(route('exam-session.summary', $exam));

        $response->assertSeeInOrder(['Mastered', 'value', '10', 'Proficient']);
    }

    /** @test */
    public function users_cannot_lose_a_mastery_status(): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);

        $updateSession['current_question'] = $session->question_count;
        $updateSession['correct_answers'] = ceil($session->question_count / 2);
        $updateSession['incorrect_answers'] = floor($session->question_count / 2);
        DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);

        $data['highest_mastery'] = 4;
        DB::table('exam_records')->where('user_id', $user->id)->where('set_id', $exam->id)->update($data);

        $response = $this->get(route('exam-session.summary', $exam));

        $data['user_id'] = $user->id;
        $this->assertDatabaseHas('exam_records', $data);
    }

    /** @test */
    public function getting_proficient_mastery_grants_credits(): void
    {
        Config::set('test.add_proficient_architect_credits', 0.2);
        Config::set('test.add_proficient_study_credits', 0.5);
        Config::set('test.grade_proficient', 3);

        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);

        $updateSession['current_question'] = $session->question_count;
        $updateSession['correct_answers'] = ceil($session->question_count / 2);
        $updateSession['incorrect_answers'] = floor($session->question_count / 2);
        DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);

        $data['highest_mastery'] = 2;
        DB::table('exam_records')->where('user_id', $user->id)->where('set_id', $exam->id)->update($data);
        $questions = Question::where('set_id', $exam->id)->get();
        foreach ($questions as $question) {
            DB::table('user_question')->where('user_id', $user->id)->where('question_id', $question->id)->update([
                'score' => 3,
            ]);
        }

        $response = $this->get(route('exam-session.summary', $exam));

        $verifyData = ([
            'user_id' => $user->id,
            'architect' => config('mage.default_architect_credits') + config('test.add_proficient_architect_credits'),
            'study' => config('mage.default_study_credits') + config('test.add_proficient_study_credits'),
        ]);

        $this->assertDatabaseHas('credits', $verifyData);
    }

    /** @test */
    public function getting_mastered_mastery_grants_credits(): void
    {
        Config::set('test.add_mastered_architect_credits', 0.5);
        Config::set('test.add_mastered_study_credits', 1);
        Config::set('test.grade_mastered', 4);

        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();
        $session = $this->StartExamSession($user, $exam);

        $updateSession['current_question'] = $session->question_count;
        $updateSession['correct_answers'] = ceil($session->question_count / 2);
        $updateSession['incorrect_answers'] = floor($session->question_count / 2);
        DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);

        $data['highest_mastery'] = 3;
        DB::table('exam_records')->where('user_id', $user->id)->where('set_id', $exam->id)->update($data);
        $questions = Question::where('set_id', $exam->id)->get();
        foreach ($questions as $question) {
            DB::table('user_question')->where('user_id', $user->id)->where('question_id', $question->id)->update([
                'score' => 4,
            ]);
        }

        $response = $this->get(route('exam-session.summary', $exam));

        $verifyData = ([
            'user_id' => $user->id,
            'architect' => config('mage.default_architect_credits') + config('test.add_mastered_architect_credits'),
            'study' => config('mage.default_study_credits') + config('test.add_mastered_study_credits'),
        ]);

        $this->assertDatabaseHas('credits', $verifyData);
    }

    /** @test */
    public function gaining_mastery_gives_the_exam_architect_credits(): void
    {
        Config::set('test.award_the_architect_architect_credits', 1);
        Config::set('test.award_the_architect_study_credits', 0.5);
        Config::set('test.grade_mastered', 4);

        $user = $this->CreateUserAndAuthenticate();
        $architect = $this->CreateUser();
        $exam = $this->CreateSet(['user_id' => $architect->id]);
        $session = $this->StartExamSession($user, $exam);

        $updateSession['current_question'] = $session->question_count;
        $updateSession['correct_answers'] = ceil($session->question_count / 2);
        $updateSession['incorrect_answers'] = floor($session->question_count / 2);
        DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);

        $data['highest_mastery'] = 3;
        DB::table('exam_records')->where('user_id', $user->id)->where('set_id', $exam->id)->update($data);
        $questions = Question::where('set_id', $exam->id)->get();
        foreach ($questions as $question) {
            DB::table('user_question')->where('user_id', $user->id)->where('question_id', $question->id)->update([
                'score' => 4,
            ]);
        }

        $response = $this->get(route('exam-session.summary', $exam));

        $verifyData = ([
            'user_id' => $architect->id,
            'architect' => config('mage.default_architect_credits') + config('test.award_the_architect_architect_credits'),
            'study' => config('mage.default_study_credits') + config('test.award_the_architect_study_credits'),
        ]);

        $this->assertDatabaseHas('credits', $verifyData);
    }

    /** @test */
    public function starting_an_exam_costs_a_study_credit(): void
    {
        Config::set('mage.default_study_credits', 5);
        $user = $this->CreateUserAndAuthenticate();
        $architect = $this->CreateUser();
        $exam = $this->CreateSet(['user_id' => $architect->id]);

        $response = $this->get(route('exam-session.enroll', $exam));

        $verifyData = ([
            'user_id' => $user->id,
            'study' => config('mage.default_study_credits') - 1,
        ]);

        $this->assertDatabaseHas('credits', $verifyData);
    }

    /** @test */
    public function cannot_start_test_without_credits(): void
    {
        Config::set('mage.default_study_credits', 0);
        $user = $this->CreateUserAndAuthenticate();
        $architect = $this->CreateUser();
        $exam = $this->CreateSet(['user_id' => $architect->id]);

        $response = $this->get(route('exam-session.start', $exam));

        $verifyData = ([
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('exam_records', $verifyData);
    }

    /** @test */
    public function users_are_not_charaged_study_credits_for_their_own_tests(): void
    {
        Config::set('mage.default_study_credits', 0);
        $architect = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $architect->id]);

        $response = $this->get(route('exam-session.enroll', $exam));

        $verifyData = ([
            'user_id' => $architect->id,
            'set_id' => $exam->id,
        ]);

        $this->assertDatabaseHas('exam_records', $verifyData);
    }

    /** ========== HELPER FUNCTIONS ========== */
    private function getExamConfigurationFormData()
    {
        return [
            'question_count' => 1,
        ];
    }

    private function setQuestionMasteryLevel($user, $question, $mastery)
    {
        DB::table('user_question')->where('user_id', $user->id)->where('question_id', $question->id)->update(['score' => $mastery]);
    }
}
