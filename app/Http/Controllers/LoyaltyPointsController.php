<?php

namespace App\Http\Controllers;

use App\DataTransfers\Loyalty\CancelDTO;
use App\DataTransfers\Loyalty\DepositDTO;
use App\DataTransfers\Loyalty\WithdrawDTO;
use App\Services\LoyaltyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoyaltyPointsController extends Controller
{
    protected LoyaltyService $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    public function deposit(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'account_type' => 'required|in:phone,card,email',
            'account_id' => 'required|string',
            'loyalty_points_rule' => 'required|string',
            'description' => 'required|string',
            'payment_id' => 'required|string',
            'payment_amount' => 'required|numeric|min:0',
            'payment_time' => 'required|date',
        ]);

        $depositDTO = new DepositDTO($validatedData);

        try {
            $transaction = $this->loyaltyService->deposit($depositDTO);
            return response()->json($transaction, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function cancel(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'transaction_id' => 'required|integer',
            'cancellation_reason' => 'required|string',
        ]);

        $cancelDTO = new CancelDTO($validatedData);

        try {
            $this->loyaltyService->cancel($cancelDTO);
            return response()->json(['message' => 'Transaction canceled'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function withdraw(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'account_type' => 'required|in:phone,card,email',
            'account_id' => 'required|string',
            'points_amount' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        $withdrawDTO = new WithdrawDTO($validatedData);

        try {
            $transaction = $this->loyaltyService->withdraw($withdrawDTO);
            return response()->json($transaction, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
