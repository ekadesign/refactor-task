<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/***
 * @property float $points_amount
 * @property bool $canceled
 * @property string $cancellation_reason
 */
class LoyaltyPointsTransaction extends Model
{
    protected $table = 'loyalty_points_transaction';

    protected $fillable = [
        'account_id',
        'points_rule',
        'points_amount',
        'description',
        'payment_id',
        'payment_amount',
        'payment_time',
    ];

    protected $casts = [
        'canceled' => 'boolean',
    ];
}
