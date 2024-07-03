<?php

namespace App\Services;

interface LoyaltyPointsServiceInterface
{
    public function deposit(array $data);
    public function cancel(array $data);
    public function withdraw(array $data);
}
