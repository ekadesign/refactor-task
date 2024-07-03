<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\LoyaltyPointsTransaction;

class LoyaltyPointsDeposited
{
    use Dispatchable, SerializesModels;

    public $transaction;

    public function __construct(LoyaltyPointsTransaction $transaction)
    {
        $this->transaction = $transaction;
    }
}
