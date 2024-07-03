<?php

declare(strict_types=1);

namespace App\Services\LoyaltyPoints\Dto;

class WithdrawParams
{
    public function __construct(
        private string $accountType,
        private string $accountId,
        private float $pointsAmount,
        private string $description,
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

    public function getPointsAmount(): float
    {
        return $this->pointsAmount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
