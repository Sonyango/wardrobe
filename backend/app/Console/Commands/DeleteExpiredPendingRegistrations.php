<?php

namespace App\Console\Commands;

use App\Models\PendingRegistration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class DeleteExpiredPendingRegistrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired pending registrations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $deleted = PendingRegistration::whereNotNull('expires_at')
            ->where('expires_at', '<', $now)
            ->delete();

        $this->info("Deleted {$deleted} expired pending registrations.");
        Log::info("pending:cleanup - deleted {$deleted} expired pending registrations.");

        return 0;
    }
}
