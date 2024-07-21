<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Set as ExamSet;

class AssignExamToUserCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_assigns_examset_to_user_successfully()
    {
        // Create a user and an ExamSet
        $user = User::factory()->create(['email' => 'user@example.com']);
        $examSet = $this->CreateSet();

        // Run the command
        $this->artisan('exam:assign-user', ['email' => $user->email, 'examSetId' => $examSet->id])
             ->expectsOutput('ExamSet assigned to user successfully.')
             ->assertExitCode(0);

        // Assert the ExamSet was assigned to the user
        $this->assertDatabaseHas('sets', [
            'id' => $examSet->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_shows_error_when_user_not_found()
    {
        // Create an ExamSet
        $examSet = $this->CreateSet();

        // Run the command with a non-existent user's email
        $this->artisan('exam:assign-user', ['email' => 'nonexistent@example.com', 'examSetId' => $examSet->id])
             ->expectsOutput('User not found.')
             ->assertExitCode(1);

        // Assert the ExamSet user_id was not changed
        $this->assertDatabaseHas('sets', [
            'id' => $examSet->id,
            'user_id' => 0,
        ]);
    }

    /** @test */
    public function it_shows_error_when_examset_not_found()
    {
        // Create a user
        $user = User::factory()->create(['email' => 'user@example.com']);

        // Run the command with a non-existent ExamSet ID
        $this->artisan('exam:assign-user', ['email' => $user->email, 'examSetId' => 999])
             ->expectsOutput('ExamSet not found.')
             ->assertExitCode(1);
    }
}
