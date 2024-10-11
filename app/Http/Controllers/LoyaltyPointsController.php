<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\LoyaltyPointsReceived;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use App\DTO\PaymentLoyaltyPointsDto;
use App\Exceptions\AccountNotActiveException;
use App\Http\Requests\CancelLoyaltyPointsRequest;
use App\Http\Requests\PaymentLoyaltyPointsRequest;
use App\Http\Resources\LoyaltyPointsTransactionResource;
use App\Services\LoyaltyPointsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LoyaltyPointsController extends Controller
{
    public function __construct(
        private LoyaltyPointsService $loyaltyPointsService
    ) {}

    public function deposit(PaymentLoyaltyPointsRequest $request): LoyaltyPointsTransactionResource|JsonResponse
    {
        $paymentLoyaltyPointsDto = new PaymentLoyaltyPointsDto($request->validated());

        Log::info('Deposit transaction input: ' . print_r($paymentLoyaltyPointsDto->toArray(), true));

        try {
            return $this->loyaltyPointsService->addLoyaltyPoints($paymentLoyaltyPointsDto);
        } catch (ModelNotFoundException) {
            Log::info('Account is not found');
            return response()->json(['message' => 'Account is not found'], Response::HTTP_NOT_FOUND);
        } catch (AccountNotActiveException $e) {
            Log::info($e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function cancel(CancelLoyaltyPointsRequest $request)
    {
        $data = $_POST;

        $reason = $data['cancellation_reason'];

        if ($reason == '') {
            return response()->json(['message' => 'Cancellation reason is not specified'], 400);
        }

        if ($transaction = LoyaltyPointsTransaction::where('id', '=', $data['transaction_id'])->where('canceled', '=', 0)->first()) {
            $transaction->canceled = time();
            $transaction->cancellation_reason = $reason;
            $transaction->save();
        } else {
            return response()->json(['message' => 'Transaction is not found'], 400);
        }
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

                    $transaction = LoyaltyPointsTransaction::withdrawLoyaltyPoints($account->id, $data['points_amount'], $data['description']);
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
