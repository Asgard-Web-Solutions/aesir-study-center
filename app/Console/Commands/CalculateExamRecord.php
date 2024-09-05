<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CalculateExamRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-exam-record {user} {exam}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the ExamRecord statistics for a particular user\'s exams';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
    }
}
