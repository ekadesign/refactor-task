<?php

namespace App\Services;

use App\Http\Requests\LoyaltyPointsCancelRequest;
use App\Http\Requests\LoyaltyPointsDepositRequest;
use App\Http\Requests\LoyaltyPointsWithdrawRequest;
use App\Mail\LoyaltyPointsReceived;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class LoyaltyPointsService
{
    public function deposit(LoyaltyPointsDepositRequest $request)
    {
        $account = $this->getAccount($request->account_type, $request->account_id);
        $transaction = LoyaltyPointsTransaction::performPaymentLoyaltyPoints(
            $account->id,
            $request->loyalty_points_rule,
            $request->description,
            $request->payment_id,
            $request->payment_amount,
            $request->payment_time
        );
        $this->notifyMail($account, $transaction);
        $this->notifySMS($account, $transaction);
        Log::info($transaction);
        return $transaction;
    }

    public function cancel(LoyaltyPointsCancelRequest $request)
    {
        $transaction = LoyaltyPointsTransaction::where('id', '=', $request->transaction_id)->where('canceled', '=', 0)->first();
        $transaction->canceled = time();
        $transaction->cancellation_reason = $request->cancellation_reason;
        $transaction->save();
    }

    public function withdraw(LoyaltyPointsWithdrawRequest $request)
    {
        $account = $this->getAccount($request->account_type, $request->account_id);
        if ($account->getBalance() < $request->points_amount) {
            $message = 'Insufficient funds: ' . $request->points_amount;
        } else {
            $transaction = LoyaltyPointsTransaction::withdrawLoyaltyPoints($account->id, $request->points_amount, $request->desciption);
            Log::info($transaction);
            return $transaction;
        }

        Log::info($message);
        return response()->json(['message' => $message], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param $account
     * @param $transaction
     */
    private function notifyMail($account, $transaction): void
    {
        if ($account->email != '' && $account->email_notification) {
            Mail::to($account)->send(new LoyaltyPointsReceived($transaction->points_amount, $account->getBalance()));
        }
    }

    /**
     * @param $account
     * @param $transaction
     */
    private function notifySMS($account, $transaction): void
    {
        if ($account->phone != '' && $account->phone_notification) {
            // instead SMS component
            Log::info('You received' . $transaction->points_amount . 'Your balance' . $account->getBalance());
        }
    }

    /**
     * @param LoyaltyPointsCancelRequest $request
     *
     * @return mixed
     */
    protected function getAccount($account_type, $account_id)
    {
        return LoyaltyAccount::where($account_type, '=', $account_id)->first();
    }
}
