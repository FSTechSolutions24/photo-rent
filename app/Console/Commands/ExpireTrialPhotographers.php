<?php

namespace App\Console\Commands;

use App\Models\Photographer;
use Illuminate\Console\Command;

class ExpireTrialPhotographers extends Command
{
    protected $signature = 'trials:expire';

    protected $description = 'Deactivate photographers whose free trial has ended';

    public function handle()
    {
        $expired = Photographer::where('is_trial', true)
            ->where('active', true)
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<=', now())
            ->update(['active' => false]);

        $this->info($expired . ' trial account(s) deactivated.');

        return self::SUCCESS;
    }
}
