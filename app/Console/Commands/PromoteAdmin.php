<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:promote {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote a user to an admin.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $userEmail = $this->argument('email');

        $user = User::where('email', $userEmail)->first();
        $user->isAdmin = 1;
        $user->save();

        $this->info('User promoted to Admin!');
    }
}
