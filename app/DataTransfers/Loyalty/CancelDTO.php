<?php

namespace App\DataTransfers\Loyalty;

class CancelDTO
{
    public int $transaction_id;
    public string $cancellation_reason;

    public function __construct(array $data)
    {
        $this->transaction_id = (int) $data['transaction_id'];
        $this->cancellation_reason = $data['cancellation_reason'];
    }
}
