<?php

declare(strict_types=1);

namespace App\Services\LoyaltyPoints;

use App\Models\LoyaltyPointsRule;

class CalculateService
{
    public function calculatePointsByRule(float $paymentAmount, ?LoyaltyPointsRule $rule): float
    {
        if (!$rule) {
            return 0;
        }

        return match ($rule->accrual_type) {
            LoyaltyPointsRule::ACCRUAL_TYPE_RELATIVE_RATE => ($paymentAmount / 100) * $rule->accrual_value,
            LoyaltyPointsRule::ACCRUAL_TYPE_ABSOLUTE_POINTS_AMOUNT => $rule->accrual_value
        };
    }
}
