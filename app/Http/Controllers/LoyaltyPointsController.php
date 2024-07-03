<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoyaltyPoints\CancelRequest;
use App\Http\Requests\LoyaltyPoints\DepositRequest;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use App\Services\LoyaltyPoints\Dto\DepositParams;
use App\Services\LoyaltyPoints\Exceptions\InvalidAccountException;
use App\Services\LoyaltyPoints\Exceptions\InvalidCancelParamException;
use App\Services\LoyaltyPoints\LoyaltyPointsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LoyaltyPointsController extends Controller
{
    public function __construct(
        private LoyaltyPointsService $loyaltyPointsService,
    ) {
    }

    public function deposit(DepositRequest $request): JsonResponse
    {
        try {
            $transaction = $this->loyaltyPointsService->deposit(new DepositParams(
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
            $this->loyaltyPointsService->cancelByTransactionId(
                $request->transaction_id,
                $request->cancellation_reason
            );
        } catch (InvalidCancelParamException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json();
    }

    public function withdraw()
    {
        $data = $_POST;

        Log::info('Withdraw loyalty points transaction input: ' . print_r($data, true));

        $type = $data['account_type'];
        $id = $data['account_id'];
        if (($type == 'phone' || $type == 'card' || $type == 'email') && $id != '') {
            if ($account = LoyaltyAccount::where($type, '=', $id)->first()) {
                if ($account->active) {
                    if ($data['points_amount'] <= 0) {
                        Log::info('Wrong loyalty points amount: ' . $data['points_amount']);
                        return response()->json(['message' => 'Wrong loyalty points amount'], 400);
                    }
                    if ($account->getBalance() < $data['points_amount']) {
                        Log::info('Insufficient funds: ' . $data['points_amount']);
                        return response()->json(['message' => 'Insufficient funds'], 400);
                    }

                    $transaction = LoyaltyPointsTransaction::withdrawLoyaltyPoints($account->id, $data['points_amount'],
                        $data['description']);
                    Log::info($transaction);
                    return $transaction;
                } else {
                    Log::info('Account is not active: ' . $type . ' ' . $id);
                    return response()->json(['message' => 'Account is not active'], 400);
                }
            } else {
                Log::info('Account is not found:' . $type . ' ' . $id);
                return response()->json(['message' => 'Account is not found'], 400);
            }
        } else {
            Log::info('Wrong account parameters');
            throw new \InvalidArgumentException('Wrong account parameters');
        }
    }
}
