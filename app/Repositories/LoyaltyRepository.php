<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\PaymentLoyaltyPointsDto;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use App\Repositories\Interfaces\LoyaltyRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LoyaltyRepository implements LoyaltyRepositoryInterface
{
    public function getLoyaltyAccount(string $accountType, int $id): Model|Builder
    {
        return LoyaltyAccount::query()->where($accountType, $id)->firstOrFail();
    }

    public function paymentLoyaltyPoints(PaymentLoyaltyPointsDto $paymentPointsDto): Model|Builder
    {
        return LoyaltyPointsTransaction::performPaymentLoyaltyPoints($paymentPointsDto);
    }

}
