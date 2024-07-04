<?php

namespace App\Http\Controllers;

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
        try {
            $transaction = $this->loyaltyService->deposit($request->all());
            return response()->json($transaction, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function cancel(Request $request): JsonResponse
    {
        try {
            $this->loyaltyService->cancel($request->all());
            return response()->json(['message' => 'Transaction canceled'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function withdraw(Request $request): JsonResponse
    {
        try {
            $transaction = $this->loyaltyService->withdraw($request->all());
            return response()->json($transaction, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
