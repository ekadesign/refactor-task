<?php

namespace App\DTO;

class DepositDTO
{
    public $account_type;
    public $account_id;
    public $loyalty_points_rule;
    public $description;
    public $payment_id;
    public $payment_amount;
    public $payment_time;

    public function __construct(array $data)
    {
        $this->account_type = $data['account_type'];
        $this->account_id = $data['account_id'];
        $this->loyalty_points_rule = $data['loyalty_points_rule'];
        $this->description = $data['description'];
        $this->payment_id = $data['payment_id'];
        $this->payment_amount = $data['payment_amount'];
        $this->payment_time = $data['payment_time'];
    }
}
