<?php

namespace App\Repositories;

interface LoyaltyPointsTransactionRepositoryInterface
{
    public function performPaymentLoyaltyPoints($accountId, $rule, $description, $paymentId, $paymentAmount, $paymentTime);
    public function findActiveById($transactionId);
    public function withdrawLoyaltyPoints($accountId, $pointsAmount, $description);
}
