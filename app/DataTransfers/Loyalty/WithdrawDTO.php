<?php

namespace App\DataTransfers\Loyalty;

class WithdrawDTO
{
    public string $account_type;
    public string $account_id;
    public float $points_amount;
    public string $description;

    public function __construct(array $data)
    {
        $this->account_type = $data['account_type'];
        $this->account_id = $data['account_id'];
        $this->points_amount = (float) $data['points_amount'];
        $this->description = $data['description'];
    }
}
