<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\LoyaltyPointsRule;
use App\Repositories\LoyaltyPointsRuleRepository as Repository;

class LoyaltyPointsRuleRepository implements Repository
{
    public function findByAlias(string $alias): ?LoyaltyPointsRule
    {
        /** @var LoyaltyPointsRule|null $rule */
        $rule = LoyaltyPointsRule::query()->where('points_rule', '=', $alias)->first();

        return $rule;
    }
}
