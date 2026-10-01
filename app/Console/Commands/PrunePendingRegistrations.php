<?php

namespace App\Console\Commands;

use App\Models\PendingRegistration;
use Illuminate\Console\Command;

class PrunePendingRegistrations extends Command
{
    protected $signature = 'registrations:prune-pending';

    protected $description = 'Delete expired, incomplete user registrations';

    public function handle(): int
    {
        $deleted = PendingRegistration::query()
            ->where('otp_expires_at', '<', now()->subHour())
            ->delete();

        $this->info("Deleted {$deleted} expired pending registration(s).");

        return self::SUCCESS;
    }
}
