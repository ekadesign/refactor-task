<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;

class LoyaltyPointsDeposited
{
    public function __construct(
        private LoyaltyAccount $account,
        private LoyaltyPointsTransaction $transaction
    )
    {
    }

    public function getAccount(): LoyaltyAccount
    {
        return $this->account;
    }

    public function getTransaction(): LoyaltyPointsTransaction
    {
        return $this->transaction;
    }
}
