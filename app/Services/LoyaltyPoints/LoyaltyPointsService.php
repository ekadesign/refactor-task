<?php

declare(strict_types=1);

namespace App\Services\LoyaltyPoints;

use App\Events\LoyaltyPointsDeposited;
use App\Models\LoyaltyPointsTransaction;
use App\Repositories\Eloquent\LoyaltyPointsRuleRepository;
use App\Repositories\LoyaltyAccountRepository;
use App\Repositories\LoyaltyPointsTransactionRepository;
use App\Services\LoyaltyPoints\Dto\DepositParams;
use App\Services\LoyaltyPoints\Exceptions\InvalidAccountException;
use App\Services\LoyaltyPoints\Exceptions\InvalidCancelParamException;
use App\ValueObjects\LoyaltyAccountNaturalId;
use InvalidArgumentException;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Psr\Log\LoggerInterface;

class LoyaltyPointsService
{
    public function __construct(
        private LoggerInterface $logger,
        private EventDispatcher $eventDispatcher,
        private LoyaltyAccountRepository $accountRepository,
        private LoyaltyPointsRuleRepository $ruleRepository,
        private LoyaltyPointsTransactionRepository $transactionRepository,
        private LoyaltyPointsCalculateService $calculateService,
    ) {
    }

    /**
     * @throws InvalidAccountException
     */
    public function deposit(DepositParams $depositParams): LoyaltyPointsTransaction
    {
        $this->logger->info('Deposit transaction input: ' . print_r($depositParams, true));

        if (!LoyaltyAccountNaturalId::isValidaType($depositParams->getAccountType())) {
            $this->logger->info('Wrong account parameters');
            throw new InvalidArgumentException('Wrong account parameters');
        }

        $account = $this->accountRepository->findByNaturalId(new LoyaltyAccountNaturalId(
            $depositParams->getAccountType(),
            $depositParams->getAccountId()
        ));
        if (!$account) {
            $this->logger->info('Account is not found');
            throw new InvalidAccountException('Account is not found');
        }

        if (!$account->active) {
            $this->logger->info('Account is not active');
            throw new InvalidAccountException('Account is not active');
        }

        $pointsRule = $this->ruleRepository->findByAlias($depositParams->getLoyaltyPointsRuleAlias());
        $pointsAmount = $this->calculateService->calculatePointsByRule(
            $depositParams->getPaymentAmount(),
            $pointsRule
        );

        $transaction = $this->transactionRepository->save(new LoyaltyPointsTransaction([
            'account_id' => $account->id,
            'points_rule' => $pointsRule?->id,
            'points_amount' => $pointsAmount,
            'description' => $depositParams->getDescription(),
            'payment_id' => $depositParams->getPaymentId(),
            'payment_amount' => $depositParams->getPaymentAmount(),
            'payment_time' => $depositParams->getPaymentTime(),
        ]));
        $this->logger->info($transaction);

        $this->eventDispatcher->dispatch(new LoyaltyPointsDeposited($account, $transaction));

        return $transaction;
    }

    /**
     * @throws InvalidCancelParamException
     */
    public function cancelByTransactionId(int $transactionId, string $reason): LoyaltyPointsTransaction
    {
        $transaction = $this->transactionRepository->findById($transactionId);
        if (!$transaction || $transaction->canceled) {
            throw new InvalidCancelParamException('Transaction is not found');
        }

        if (empty($reason)) {
            throw new InvalidCancelParamException('Cancellation reason is not specified');
        }

        $transaction->canceled = time();
        $transaction->cancellation_reason = $reason;
        $this->transactionRepository->save($transaction);

        return $transaction;
    }
}
