<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\LoyaltyPointsTransaction;

interface LoyaltyPointsTransactionRepository
{
    public function save(LoyaltyPointsTransaction $transaction): LoyaltyPointsTransaction;

    public function findById(int $id): ?LoyaltyPointsTransaction;
}
