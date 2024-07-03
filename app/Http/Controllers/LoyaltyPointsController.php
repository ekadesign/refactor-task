<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoyaltyPoints\CancelRequest;
use App\Http\Requests\LoyaltyPoints\DepositRequest;
use App\Http\Requests\LoyaltyPoints\WithdrawRequest;
use App\Services\LoyaltyPoints\Dto\DepositParams;
use App\Services\LoyaltyPoints\Dto\WithdrawParams;
use App\Services\LoyaltyPoints\Exceptions\InvalidAccountException;
use App\Services\LoyaltyPoints\Exceptions\InvalidCancelParamException;
use App\Services\LoyaltyPoints\BalanceService;
use App\Services\LoyaltyPoints\Exceptions\InvalidPointsAmountException;
use Illuminate\Http\JsonResponse;

class LoyaltyPointsController extends Controller
{
    public function __construct(
        private BalanceService $balanceService,
    ) {
    }

    public function deposit(DepositRequest $request): JsonResponse
    {
        try {
            $transaction = $this->balanceService->deposit(new DepositParams(
                accountType: $request->account_type,
                accountId: $request->account_id,
                loyaltyPointsRuleAlias: $request->loyalty_points_rule,
                description: $request->description,
                paymentId: $request->payment_id,
                paymentAmount: $request->payment_amount,
                paymentTime: $request->payment_time
            ));
        } catch (InvalidAccountException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json($transaction);
    }

    public function cancel(CancelRequest $request): JsonResponse
    {
        try {
            $this->balanceService->cancelByTransactionId(
                $request->transaction_id,
                $request->cancellation_reason
            );
        } catch (InvalidCancelParamException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json();
    }

    public function withdraw(WithdrawRequest $request): JsonResponse
    {
        try {
            $transaction = $this->balanceService->withdraw(new WithdrawParams(
                accountType: $request->account_type,
                accountId: $request->account_id,
                pointsAmount: $request->points_amount,
                description: $request->description,
            ));
        } catch (InvalidAccountException|InvalidPointsAmountException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json($transaction);
    }
}
