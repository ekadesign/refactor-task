<?php

namespace App\DTO;

class WithdrawDTO
{
    public $account_type;
    public $account_id;
    public $points_amount;
    public $description;

    public function __construct(array $data)
    {
        $this->account_type = $data['account_type'];
        $this->account_id = $data['account_id'];
        $this->points_amount = $data['points_amount'];
        $this->description = $data['description'];
    }
}
