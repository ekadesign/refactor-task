<?php

declare(strict_types=1);

namespace App\Services\LoyaltyPoints\Dto;

use App\ValueObjects\LoyaltyAccountNaturalId;

class DepositParams
{
    public function __construct(
        private string $accountType,
        private string $accountId,
        private string $loyaltyPointsRuleAlias,
        private string $description,
        private string $paymentId,
        private float $paymentAmount,
        private int $paymentTime,
    )
    {
    }

    public function getAccountType(): string
    {
        return $this->accountType;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getLoyaltyPointsRuleAlias(): string
    {
        return $this->loyaltyPointsRuleAlias;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function getPaymentAmount(): float
    {
        return $this->paymentAmount;
    }

    public function getPaymentTime(): int
    {
        return $this->paymentTime;
    }
}
