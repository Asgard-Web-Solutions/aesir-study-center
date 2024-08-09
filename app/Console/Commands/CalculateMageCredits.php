<?php

namespace App\Console\Commands;

use App\Models\Credit;
use App\Models\User;
use Illuminate\Console\Command;

class CalculateMageCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mage:calculate-credits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds any users that do not have credits and adds them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            if (!$user->credit) {
                $credit = new Credit([
                    'architect' => config('mage.default_architect_credits'),
                    'publish' => config('mage.default_publish_credits'),
                    'question' => config('mage.default_question_credits'),
                    'study' => config('mage.default_study_credits'),
                ]);

                $user->credit()->save($credit);
            }
        }
    }
}
