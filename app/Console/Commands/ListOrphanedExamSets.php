<?php

namespace App\Console\Commands;

use App\Models\Set as ExamSet;
use Illuminate\Console\Command;

class ListOrphanedExamSets extends Command
{
    protected $signature = 'exam:list-orphaned';

    protected $description = 'List all ExamSets with a user_id of null or zero';

    public function handle(): void
    {
        // Fetch ExamSets with user_id of null or zero
        $orphanedExamSets = ExamSet::where(function ($query) {
            $query->whereNull('user_id')
                ->orWhere('user_id', 0);
        })->get();

        if ($orphanedExamSets->isEmpty()) {
            $this->info('No orphaned ExamSets found.');
        } else {
            $this->info('Orphaned ExamSets:');
            $this->table(['ID', 'Name', 'User ID'], $orphanedExamSets->map(function ($examSet) {
                return [
                    $examSet->id,
                    $examSet->name,
                    $examSet->user_id,
                ];
            }));
        }
    }
}
