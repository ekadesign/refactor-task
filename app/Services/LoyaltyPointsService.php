<?php

namespace App\Services;

use App\Events\LoyaltyPointsDeposited;
use App\DTO\DepositDTO;
use App\DTO\WithdrawDTO;
use App\Repositories\LoyaltyAccountRepositoryInterface;
use App\Repositories\LoyaltyPointsTransactionRepositoryInterface;
use Illuminate\Support\Facades\Log;

class LoyaltyPointsService implements LoyaltyPointsServiceInterface
{
    protected LoyaltyAccountRepositoryInterface $loyaltyAccountRepository;
    protected LoyaltyPointsTransactionRepositoryInterface $loyaltyPointsTransactionRepository;

    public function __construct(
        LoyaltyAccountRepositoryInterface $loyaltyAccountRepository,
        LoyaltyPointsTransactionRepositoryInterface $loyaltyPointsTransactionRepository
    ) {
        $this->loyaltyAccountRepository = $loyaltyAccountRepository;
        $this->loyaltyPointsTransactionRepository = $loyaltyPointsTransactionRepository;
    }

    public function deposit(array $data)
    {
        $dto = new DepositDTO($data);

        $account = $this->loyaltyAccountRepository->findByTypeAndId($dto->account_type, $dto->account_id);

        if (!$account || !$account->active) {
            Log::warning('Account is not found or not active', ['account_type' => $dto->account_type, 'account_id' => $dto->account_id]);
            throw new \Exception('Account is not found or not active');
        }

        $transaction = $this->loyaltyPointsTransactionRepository->performPaymentLoyaltyPoints(
            $account->id,
            $dto->loyalty_points_rule,
            $dto->description,
            $dto->payment_id,
            $dto->payment_amount,
            $dto->payment_time
        );

        Log::info('Loyalty points deposited', ['transaction' => $transaction]);

        event(new LoyaltyPointsDeposited($transaction));

        return $transaction;
    }

    public function cancel(array $data)
    {
        $transaction = $this->loyaltyPointsTransactionRepository->findActiveById($data['transaction_id']);

        if (!$transaction) {
            Log::warning('Transaction is not found', ['transaction_id' => $data['transaction_id']]);
            throw new \Exception('Transaction is not found');
        }

        $transaction->canceled = time();
        $transaction->cancellation_reason = $data['cancellation_reason'];
        $transaction->save();

        Log::info('Transaction canceled', ['transaction' => $transaction]);
    }

    public function withdraw(array $data)
    {
        $dto = new WithdrawDTO($data);

        $account = $this->loyaltyAccountRepository->findByTypeAndId($dto->account_type, $dto->account_id);

        if (!$account || !$account->active) {
            Log::warning('Account is not found or not active', ['account_type' => $dto->account_type, 'account_id' => $dto->account_id]);
            throw new \Exception('Account is not found or not active');
        }

        if ($account->getBalance() < $dto->points_amount) {
            Log::warning('Insufficient funds', ['account_id' => $dto->account_id, 'points_amount' => $dto->points_amount]);
            throw new \Exception('Insufficient funds');
        }

        $transaction = $this->loyaltyPointsTransactionRepository->withdrawLoyaltyPoints(
            $account->id,
            $dto->points_amount,
            $dto->description
        );

        Log::info('Loyalty points withdrawn', ['transaction' => $transaction]);

        return $transaction;
    }
}
