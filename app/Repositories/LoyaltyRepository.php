<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\CancelLoyaltyPointsDto;
use App\DTO\PaymentLoyaltyPointsDto;
use App\DTO\WithdrawLoyaltyPointsDto;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use App\Repositories\Interfaces\LoyaltyRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function cancelLoyaltyPoints(CancelLoyaltyPointsDto $cancelDto): Model|Builder
    {
        $updated = LoyaltyPointsTransaction::query()
            ->where('id', $cancelDto->getTransactionId())
            ->where('canceled', 0)
            ->firstOrFail();

        try {
            DB::transaction(function () use ($updated, $cancelDto) {
                $updated->update([
                    'canceled' => time(),
                    'cancellation_reason' => $cancelDto->getReason(),
                ]);
                return $updated;
            });
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }

        return $updated;
    }

    public function withdrawLoyaltyPoints(WithdrawLoyaltyPointsDto $withdrawDto): Model|Builder
    {
        return LoyaltyPointsTransaction::withdrawLoyaltyPoints($withdrawDto);
    }
}
