<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\CancelLoyaltyPointsDto;
use App\DTO\PaymentLoyaltyPointsDto;
use App\DTO\WithdrawLoyaltyPointsDto;
use App\Events\PaymentLoyaltyPointsNotifications;
use App\Exceptions\AccountNotActiveException;
use App\Http\Resources\LoyaltyPointsTransactionResource;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use App\Repositories\Interfaces\LoyaltyRepositoryInterface;
use Illuminate\Support\Facades\Log;

class LoyaltyPointsService
{
    public function __construct(
        private LoyaltyRepositoryInterface $loyaltyPointsRepo
    ) {}

    /**
     * @throws AccountNotActiveException
     */
    public function addLoyaltyPoints(PaymentLoyaltyPointsDto $paymentLoyaltyPointsDto): LoyaltyPointsTransactionResource
    {
        /** @var LoyaltyAccount $account */
        $account = $this->loyaltyPointsRepo->getLoyaltyAccount(
            $paymentLoyaltyPointsDto->getAccountType(),
            $paymentLoyaltyPointsDto->getId()
        );

        if (!$account->isActive()) {
            throw new AccountNotActiveException();
        }

        /** @var LoyaltyPointsTransaction $transaction */
        $transaction = $this->loyaltyPointsRepo->paymentLoyaltyPoints($paymentLoyaltyPointsDto);
        Log::info('Transaction', $paymentLoyaltyPointsDto->toArray());

        event(new PaymentLoyaltyPointsNotifications($account, $transaction));

        return new LoyaltyPointsTransactionResource($transaction);
    }

    public function rollbackLoyaltyPoints(CancelLoyaltyPointsDto $cancelDto): LoyaltyPointsTransactionResource
    {
        return new LoyaltyPointsTransactionResource($this->loyaltyPointsRepo->cancelLoyaltyPoints($cancelDto));
    }

    /**
     * @throws AccountNotActiveException
     */
    public function withdrawLoyaltyPoints(WithdrawLoyaltyPointsDto $withdrawLoyaltyPointsDto): LoyaltyPointsTransactionResource
    {
        /** @var LoyaltyAccount $account */
        $account = $this->loyaltyPointsRepo->getLoyaltyAccount(
            $withdrawLoyaltyPointsDto->getAccountType(),
            $withdrawLoyaltyPointsDto->getId()
        );

        if (!$account->isActive()) {
            throw new AccountNotActiveException();
        }

        /** @var LoyaltyPointsTransaction $transaction */
        $transaction = $this->loyaltyPointsRepo->withdrawLoyaltyPoints($withdrawLoyaltyPointsDto);
        Log::info('Transaction', $withdrawLoyaltyPointsDto->toArray());

        return new LoyaltyPointsTransactionResource($transaction);
    }
}
