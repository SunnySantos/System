<?php

namespace App\Console\Commands;

use App\Enums\UserStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeactivateInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:deactivate-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate users who have not logged in for 30 days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = Carbon::now()->subDays(30);

        Log::debug('DeactivateInactiveUsers');

        $count = User::where('status', '!=', UserStatus::Inactive)
            ->where(function ($query) use ($threshold) {
                $query->whereNull('last_login_at')
                    ->orWhere('last_login_at', '<', $threshold);
            })
            ->update(['status' => UserStatus::Inactive]);

        $this->info("✅ {$count} user(s) deactivated for inactivity.");
        return Command::SUCCESS;
    }
}
