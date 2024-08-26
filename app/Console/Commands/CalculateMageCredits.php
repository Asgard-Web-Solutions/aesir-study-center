<?php

namespace App\Console\Commands;

use App\Actions\User\RecordCreditHistory;
use App\Models\Credit;
use App\Models\CreditHistory;
use App\Models\User;
use Illuminate\Console\Command;

class CalculateMageCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-new-credits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds any users that do not have credits and adds them';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            if (! $user->credit) {

                $credits['architect'] = config('mage.default_architect_credits');
                $credits['study'] = config('mage.default_study_credits');

                $credit = new Credit([
                    'architect' => $credits['architect'],
                    'study' => $credits['study'],
                ]);

                $user->credit()->save($credit);

                $history = RecordCreditHistory::execute($user, 'Acolyte Enrollment', 'Credits received for enrolling at Acolyte Academy.', $credits);
            }
        }
    }
}
