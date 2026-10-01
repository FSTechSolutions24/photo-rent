<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Require an authenticated admin session in every environment.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        Telescope::auth(function ($request) {
            $user = $request->user();

            return $user && $user->type === 'admin';
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
            // Always record entries for local development.
            if ($this->app->environment('local')) {
                return true;
            }

            // Record all entries generated during an admin request.
            $user = auth()->user();

            if ($user && $user->type === 'admin') {
                return true;
            }

            // Otherwise, only record critical events.
            return $entry->isReportableException() ||
                $entry->isFailedRequest() ||
                $entry->isFailedJob() ||
                $entry->isScheduledTask() ||
                $entry->hasMonitoredTag();
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate mirrors the Telescope authorization callback.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return $user->type === 'admin';
        });
    }
}
