<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoyaltyPointsCancelRequest;
use App\Http\Requests\LoyaltyPointsDepositRequest;
use App\Http\Requests\LoyaltyPointsWithdrawRequest;
use App\Http\Resources\LoyaltyPointsTransactionResource;
use App\Services\LoyaltyPointsService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LoyaltyPointsController extends Controller
{
    public function deposit(LoyaltyPointsDepositRequest $request, LoyaltyPointsService $service): JsonResponse|LoyaltyPointsTransactionResource
    {
        $transaction = $service->performPaymentLoyaltyPoints($request->validated());

        return LoyaltyPointsTransactionResource::make($transaction);
    }

    public function cancel(LoyaltyPointsCancelRequest $request, LoyaltyPointsService $service): JsonResponse|LoyaltyPointsTransactionResource
    {
        $transaction = $service->cancelTransaction($request->validated());

        return LoyaltyPointsTransactionResource::make($transaction);
    }

    public function withdraw(LoyaltyPointsWithdrawRequest $request, LoyaltyPointsService $service): JsonResponse|LoyaltyPointsTransactionResource
    {
        try {
            $transaction = $service->withdrawLoyaltyPoints($request->validated());

            return LoyaltyPointsTransactionResource::make($transaction);
        } catch (Exception $e) {
            Log::info($e->getMessage());

            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
