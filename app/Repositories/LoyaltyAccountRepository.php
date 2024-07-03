<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\LoyaltyAccount;
use App\ValueObjects\LoyaltyAccountNaturalId;

interface LoyaltyAccountRepository
{
    public function findByNaturalId(LoyaltyAccountNaturalId $id): ?LoyaltyAccount;
}
