<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyPointsTransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'points_rule' => $this->points_rule,
            'points_amount' => $this->points_amount,
            'description' => $this->description,
            'payment_id' => $this->payment_id,
            'payment_amount' => $this->payment_amount,
            'payment_time' => $this->payment_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
