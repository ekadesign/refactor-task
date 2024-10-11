<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTO\PaymentLoyaltyPointsDto;

interface LoyaltyRepositoryInterface
{
    public function getLoyaltyAccount(string $accountType, int $id);
    public function paymentLoyaltyPoints(PaymentLoyaltyPointsDto $paymentPointsDto);
}
