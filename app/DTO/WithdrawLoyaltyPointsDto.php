<?php

declare(strict_types=1);

namespace App\DTO;

class WithdrawLoyaltyPointsDto
{
    private int $id;
    private string $accountType;
    private string $description;
    private float $pointsAmount;

    public function __construct(array $validatedData)
    {
    }

    public function toArray(): array
    {
        return [];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAccountType(): string
    {
        return $this->accountType;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPointsAmount(): float
    {
        return $this->pointsAmount;
    }
}
