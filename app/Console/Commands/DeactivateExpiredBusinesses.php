<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Business;

class DeactivateExpiredBusinesses extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'business:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate businesses whose approval has expired after 1 year';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $expiredCount = Business::where('status', 'approved')
            ->whereNotNull('approved_at')
            ->where('approved_at', '<=', now()->subYear())
            ->update(['status' => 'inactive']);

        $this->info("Successfully deactivated {$expiredCount} expired business(es).");
    }
}
