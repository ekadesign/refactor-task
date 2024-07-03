<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\LoyaltyPointsTransaction;
use App\Repositories\LoyaltyPointsTransactionRepository as Repository;

class LoyaltyPointsTransactionRepository implements Repository
{
    public function create(LoyaltyPointsTransaction $transaction): LoyaltyPointsTransaction
    {
        $transaction->save();

        return $transaction;
    }
}
