<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $accrual_type
 * @property float $accrual_value
 */
class LoyaltyPointsRule extends Model
{
    public const ACCRUAL_TYPE_RELATIVE_RATE = 'relative_rate';
    public const ACCRUAL_TYPE_ABSOLUTE_POINTS_AMOUNT = 'absolute_points_amount';

    protected $table = 'loyalty_points_rule';

    protected $fillable = [
        'points_rule',
        'accrual_type',
        'accrual_value',
    ];
}
