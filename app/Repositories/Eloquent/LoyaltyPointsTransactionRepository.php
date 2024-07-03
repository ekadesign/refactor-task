<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\LoyaltyPointsTransaction;
use App\Repositories\LoyaltyPointsTransactionRepository as Repository;

class LoyaltyPointsTransactionRepository implements Repository
{
    public function save(LoyaltyPointsTransaction $transaction): LoyaltyPointsTransaction
    {
        $transaction->save();

        return $transaction;
    }

    public function findById(int $id): ?LoyaltyPointsTransaction
    {
        /** @var LoyaltyPointsTransaction|null $transaction */
        $transaction = LoyaltyPointsTransaction::query()->where('id', '=', $id)->first();

        return $transaction;
    }
}
