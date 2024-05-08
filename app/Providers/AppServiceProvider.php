<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Policies\V1\TicketPolicy;
use App\Policies\V2\TicketPolicy as V2TicketPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Ticket::class, TicketPolicy::class);
        Gate::policy(Ticket::class, V2TicketPolicy::class);
    }
}
