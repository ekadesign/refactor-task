<?php

namespace App\Listeners;

use App\Events\LoyaltyPointsDeposited;
use App\Mail\LoyaltyPointsReceived;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendLoyaltyPointsNotification
{
    public function handle(LoyaltyPointsDeposited $event)
    {
        $account = $event->transaction->account;

        if ($account->email && $account->email_notification) {
            Mail::to($account)->send(new LoyaltyPointsReceived($event->transaction->points_amount, $account->getBalance()));
        }

        if ($account->phone && $account->phone_notification) {
            // instead SMS component
            Log::info('You received ' . $event->transaction->points_amount . ' Your balance ' . $account->getBalance());
        }
    }
}
