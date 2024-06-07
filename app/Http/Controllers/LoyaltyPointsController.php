<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoyaltyPointsCancelRequest;
use App\Http\Requests\LoyaltyPointsDepositRequest;
use App\Http\Requests\LoyaltyPointsWithdrawRequest;
use App\Services\LoyaltyPointsService;

class LoyaltyPointsController extends Controller
{
    private LoyaltyPointsService $loyaltyPointsService;

    public function __construct(LoyaltyPointsService $loyaltyPointsService)
    {
        $this->loyaltyPointsService = $loyaltyPointsService;
    }

    public function deposit(LoyaltyPointsDepositRequest $request)
    {
        return $this->loyaltyPointsService->deposit($request);
    }

    public function cancel(LoyaltyPointsCancelRequest $request)
    {
        $this->loyaltyPointsService->cancel($request);
    }

    public function withdraw(LoyaltyPointsWithdrawRequest $request)
    {
        return $this->loyaltyPointsService->withdraw($request);
    }
}
