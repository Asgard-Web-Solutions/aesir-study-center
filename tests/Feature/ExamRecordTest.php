<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Question;
use Tests\TestCase;
use Carbon\Carbon;
use Config;
use DB;

class ExamRecordTest extends TestCase
{
    // DONE: When a user takes an exam for the first time, create the exam record
    /** @test */
    public function exam_record_created_when_first_taking_an_exam() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $response = $this->get(route('exam-session.start', $exam));

        $data = [
            'user_id' => $user->id,
            'set_id' => $exam->id,
        ];

        $this->assertDatabaseHas('exam_records', $data);
    }

    /** @test */
    public function exam_record_is_not_duplicated_if_already_exists() {
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
    public function user_can_start_their_own_private_exam() {
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet();

        $response = $this->get(route('exam-session.start', $exam));

        $response->assertRedirect(route('exam-session.configure', $exam));
    }

    /** @test */
    public function other_users_cannot_start_private_exams() {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 0]);

        $response = $this->get(route('exam-session.start', $exam));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function other_users_can_start_public_exams() {
        $examOwner = $this->CreateUser();
        $user = $this->CreateUserAndAuthenticate();
        $exam = $this->CreateSet(['user_id' => $examOwner->id, 'visibility' => 1]);

        $response = $this->get(route('exam-session.start', $exam));

        $response->assertRedirect(route('exam-session.configure', $exam));
    }

    // DONE: When a user completes an exam, update the exam record stats (latest grade, times taken, last completed)
    /** @test */
    public function exam_updates_times_taken_after_completing_exam() {
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
    public function a_completed_exam_is_not_counted_twice_when_viewing_the_summary_page() {
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
    public function completing_an_exam_averages_the_latest_session_grades() {
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
        for ($i = 1; $i <= 4; $i ++) {
            DB::table('exam_sessions')->insert([
                'user_id' => $user->id,
                'set_id' => $exam->id,
                'question_count' => 2,
                'current_question' => 2,
                'correct_answers' => 1,
                'incorrect_answers' => 1,
                'grade' => 50,
                'date_completed' => Carbon::now()
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
    public function exam_record_has_last_taken_set_after_completing_a_session() {
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
    public function exam_record_gets_mastery_progress_calculation() {
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
            'mastery_mastered_count'    => 1,
            'mastery_proficient_count'  => 2,
            'mastery_familiar_count'    => 3,
            'mastery_apprentice_count'  => 4,
        ];
        $this->assertDatabaseHas('exam_records', $verifyData);
    }

    
    // TODO: Write a command to generate/update the ExamRecord for a single user or all users
    
    

    /** ========== HELPER FUNCTIONS ========== */
    private function getExamConfigurationFormData() {
        return [
            'question_count' => 1,
        ];
    }
    
    private function setQuestionMasteryLevel($user, $question, $mastery) {
        DB::table('user_question')->where('user_id', $user->id)->where('question_id', $question->id)->update(['score' => $mastery]);
    }
}
