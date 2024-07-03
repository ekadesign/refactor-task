<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\CancelRequest;
use App\Http\Requests\WithdrawRequest;
use App\Services\LoyaltyPointsServiceInterface;
use Illuminate\Support\Facades\Log;
use App\DTO\DepositDTO;
use App\DTO\WithdrawDTO;

class LoyaltyPointsController extends Controller
{
    protected LoyaltyPointsServiceInterface $loyaltyPointsService;

    public function __construct(LoyaltyPointsServiceInterface $loyaltyPointsService)
    {
        $this->loyaltyPointsService = $loyaltyPointsService;
    }

    public function deposit(DepositRequest $request)
    {
        $data = $request->validated();
        $dto = new DepositDTO($data);

        Log::info('Deposit transaction input', [
            'account_type' => $dto->account_type,
            'account_id' => $dto->account_id,
            'payment_id' => $dto->payment_id,
            'payment_amount' => $dto->payment_amount,
            'payment_time' => $dto->payment_time
        ]);

        try {
            $transaction = $this->loyaltyPointsService->deposit($data);
            Log::info('Deposit transaction successful', ['transaction_id' => $transaction->id]);
            return response()->json($transaction);
        } catch (\Exception $e) {
            Log::error('Deposit transaction failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Deposit transaction failed'], 500);
        }
    }

    public function cancel(CancelRequest $request)
    {
        $data = $request->validated();

        Log::info('Cancel transaction input', ['transaction_id' => $data['transaction_id']]);

        try {
            $this->loyaltyPointsService->cancel($data);
            Log::info('Cancel transaction successful', ['transaction_id' => $data['transaction_id']]);
            return response()->json(['message' => 'Transaction canceled successfully']);
        } catch (\Exception $e) {
            Log::error('Cancel transaction failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Cancel transaction failed'], 500);
        }
    }

    public function withdraw(WithdrawRequest $request)
    {
        $data = $request->validated();
        $dto = new WithdrawDTO($data);

        Log::info('Withdraw transaction input', [
            'account_type' => $dto->account_type,
            'account_id' => $dto->account_id,
            'points_amount' => $dto->points_amount,
            'description' => $dto->description
        ]);

        try {
            $transaction = $this->loyaltyPointsService->withdraw($data);
            Log::info('Withdraw transaction successful', ['transaction_id' => $transaction->id]);
            return response()->json($transaction);
        } catch (\Exception $e) {
            Log::error('Withdraw transaction failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Withdraw transaction failed'], 500);
        }
    }
}
