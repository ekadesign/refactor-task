<?php

declare(strict_types=1);

namespace App\DTO;

class PaymentLoyaltyPointsDto
{
    private int $id;
    private string $accountType;
    private string $description;

    /**
     * @param array $validatedData
     */
    public function __construct(array $validatedData)
    {
    }

    /**
     * @return array
     */
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
}
