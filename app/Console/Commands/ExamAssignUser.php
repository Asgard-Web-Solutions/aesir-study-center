<?php

namespace App\Console\Commands;

use App\Models\Set as ExamSet;
use App\Models\User;
use Illuminate\Console\Command;

class ExamAssignUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam:assign-user {examSetId} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a test to a specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the input arguments
        $email = $this->argument('email');
        $examSetId = $this->argument('examSetId');

        // Find the user by email
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error('User not found.');

            return 1;
        }

        // Find the ExamSet by id
        $examSet = ExamSet::find($examSetId);

        if (! $examSet) {
            $this->error('ExamSet not found.');

            return 1;
        }

        // Assign the ExamSet to the user
        $examSet->user_id = $user->id;
        $examSet->save();

        $this->info('ExamSet assigned to user successfully.');

        return 0;
    }
}
