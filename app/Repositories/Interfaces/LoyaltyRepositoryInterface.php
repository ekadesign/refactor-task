<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\DTO\CancelLoyaltyPointsDto;
use App\DTO\PaymentLoyaltyPointsDto;
use App\DTO\WithdrawLoyaltyPointsDto;

interface LoyaltyRepositoryInterface
{
    public function getLoyaltyAccount(string $accountType, int $id);
    public function paymentLoyaltyPoints(PaymentLoyaltyPointsDto $paymentPointsDto);
    public function cancelLoyaltyPoints(CancelLoyaltyPointsDto $cancelDto);
    public function withdrawLoyaltyPoints(WithdrawLoyaltyPointsDto $withdrawDto);
}
