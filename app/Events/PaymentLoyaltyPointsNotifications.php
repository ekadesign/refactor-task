<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentLoyaltyPointsNotifications
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public LoyaltyAccount $account,
        public LoyaltyPointsTransaction $transaction
    ) {}

}
