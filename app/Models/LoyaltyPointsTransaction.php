<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

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

    public function scopeWithoutCancelled(Builder $query): Builder
    {
        return $query->where(['cancelled' => 0]);
    }
}
