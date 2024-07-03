<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoyaltyPoints\DepositRequest;
use App\Mail\LoyaltyPointsReceived;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoyaltyPointsController extends Controller
{
    public function deposit(DepositRequest $request): JsonResponse
    {
        Log::info('Deposit transaction input: ' . print_r($request->all(), true));

        $type = $request->account_type;
        $id = $request->account_id;
        if (($type == 'phone' || $type == 'card' || $type == 'email') && $id != '') {
            if ($account = LoyaltyAccount::where($type, '=', $id)->first()) {
                if ($account->active) {
                    $transaction =  LoyaltyPointsTransaction::performPaymentLoyaltyPoints($account->id, $request->loyalty_points_rule, $request->description, $request->payment_id, $request->payment_amount, $request->payment_time);
                    Log::info($transaction);
                    if ($account->email != '' && $account->email_notification) {
                        Mail::to($account)->send(new LoyaltyPointsReceived($transaction->points_amount, $account->getBalance()));
                    }
                    if ($account->phone != '' && $account->phone_notification) {
                        // instead SMS component
                        Log::info('You received' . $transaction->points_amount . 'Your balance' . $account->getBalance());
                    }
                    return $transaction;
                } else {
                    Log::info('Account is not active');
                    return response()->json(['message' => 'Account is not active'], 400);
                }
            } else {
                Log::info('Account is not found');
                return response()->json(['message' => 'Account is not found'], 400);
            }
        } else {
            Log::info('Wrong account parameters');
            throw new \InvalidArgumentException('Wrong account parameters');
        }
    }

    public function cancel()
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
