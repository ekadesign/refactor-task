<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PaymentLoyaltyPointsNotifications;
use App\Mail\LoyaltyPointsReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailPaymentLoyaltyPointsNotification
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

        if ($loyaltyAcc->hasEmail() && $loyaltyAcc->hasEmailNotification()) {
            Mail::to($loyaltyAcc)->send(new LoyaltyPointsReceived(
                $loyaltyTransaction->getPointsAmount(),
                $loyaltyAcc->getBalance())
            );
        }
    }
}
