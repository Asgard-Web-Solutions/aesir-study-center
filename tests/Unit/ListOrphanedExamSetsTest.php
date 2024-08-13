<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use App\Models\Set as ExamSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ListOrphanedExamSetsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_lists_all_orphaned_exam_sets(): void
    {
        // Create ExamSets with user_id null and zero
        ExamSet::factory()->create(['user_id' => null]);
        ExamSet::factory()->create(['user_id' => 0]);
        ExamSet::factory()->create(['user_id' => 1]); // Should not be listed

        // Run the command
        $this->artisan('exam:list-orphaned')
            ->expectsOutput('Orphaned ExamSets:')
            ->assertExitCode(0);
    }
}
