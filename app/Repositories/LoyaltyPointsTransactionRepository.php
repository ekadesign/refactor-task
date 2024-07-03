<?php

namespace App\Repositories;

use App\Models\LoyaltyPointsTransaction;

class LoyaltyPointsTransactionRepository implements LoyaltyPointsTransactionRepositoryInterface
{
    public function performPaymentLoyaltyPoints($accountId, $rule, $description, $paymentId, $paymentAmount, $paymentTime)
    {
        // Логика для создания транзакции
    }

    public function findActiveById($transactionId)
    {
        return LoyaltyPointsTransaction::where('id', $transactionId)->where('canceled', 0)->first();
    }

    public function withdrawLoyaltyPoints($accountId, $pointsAmount, $description)
    {
        // Логика для снятия баллов
    }
}
