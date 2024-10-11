<?php

declare(strict_types=1);

namespace App\DTO;

class CancelLoyaltyPointsDto
{
    private int $transactionId;
    private string $reason;

    public function __construct(array $validatedData)
    {
    }

    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
