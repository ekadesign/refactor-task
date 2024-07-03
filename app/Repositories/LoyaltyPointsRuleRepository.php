<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\LoyaltyPointsRule;

interface LoyaltyPointsRuleRepository
{
    public function findByAlias(string $alias): ?LoyaltyPointsRule;
}
