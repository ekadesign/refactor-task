<?php

namespace App\DataTransfers\Loyalty;

class DepositDTO
{
    public string $account_type;
    public string $account_id;
    public string $loyalty_points_rule;
    public string $description;
    public string $payment_id;
    public float $payment_amount;
    public string $payment_time;

    public function __construct(array $data)
    {
        $this->account_type = $data['account_type'];
        $this->account_id = $data['account_id'];
        $this->loyalty_points_rule = $data['loyalty_points_rule'];
        $this->description = $data['description'];
        $this->payment_id = $data['payment_id'];
        $this->payment_amount = (float)$data['payment_amount'];
        $this->payment_time = $data['payment_time'];
    }
}
