<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PaymentLoyaltyPointsNotifications;
use Illuminate\Support\Facades\Log;

class SendPhonePaymentLoyaltyPointsNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PaymentLoyaltyPointsNotifications  $event
     * @return void
     */
    public function handle(PaymentLoyaltyPointsNotifications $event)
    {
        $loyaltyAcc = $event->account;
        $loyaltyTransaction = $event->transaction;

        if ($loyaltyAcc->hasPhone() && $loyaltyAcc->hasPhoneNotification()) {
            // instead SMS component
            Log::info('You received: ' . $loyaltyTransaction->getPointsAmount() . '. ' . 'Your balance: ' . $loyaltyAcc->getBalance());
        }
    }
}
