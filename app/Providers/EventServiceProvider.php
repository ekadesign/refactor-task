<?php

namespace App\Providers;

use App\Events\PaymentLoyaltyPointsNotifications;
use App\Listeners\SendMailPaymentLoyaltyPointsNotification;
use App\Listeners\SendPhonePaymentLoyaltyPointsNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PaymentLoyaltyPointsNotifications::class => [
            SendMailPaymentLoyaltyPointsNotification::class,
            SendPhonePaymentLoyaltyPointsNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
