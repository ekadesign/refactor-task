<?php

namespace App\Services;

use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsRule;
use App\Models\LoyaltyPointsTransaction;
use Exception;
use Illuminate\Support\Facades\Log;

class LoyaltyPointsService
{

    public function getAccount(array $data): LoyaltyAccount
    {
        return LoyaltyAccount::where([$data['account_type'] => $data['account_id']])->first();
    }

    public function performPaymentLoyaltyPoints(array$data): LoyaltyPointsTransaction
    {
        $account =  $this->getAccount($data);

        $points_amount = 0;

        if ($pointsRule = LoyaltyPointsRule::where(['points_rule' => $data['loyalty_points_rule']])->first()) {
            $points_amount = match ($pointsRule->accrual_type) {
                LoyaltyPointsRule::ACCRUAL_TYPE_RELATIVE_RATE => ($data['payment_amount'] / 100) * $pointsRule->accrual_value,
                LoyaltyPointsRule::ACCRUAL_TYPE_ABSOLUTE_POINTS_AMOUNT => $pointsRule->accrual_value
            };
        }

        $transaction = LoyaltyPointsTransaction::create([
            'account_id' => $account->id,
            'points_rule' => $pointsRule?->id,
            'points_amount' => $points_amount,
            'description' => $data['description'],
            'payment_id' => $data['payment_id'],
            'payment_amount' => $data['payment_amount'],
            'payment_time' => $data['payment_time'],
        ]);

        Log::info($transaction);

        $account->notify();

        return $transaction;
    }

    public function cancelTransaction(array $data): LoyaltyPointsTransaction
    {
        $transaction = LoyaltyPointsTransaction::where([
            'id' => $data['transaction_id'],
            'cancelled' => 0
        ])->first();

        $transaction->canceled = time();
        $transaction->cancellation_reason = $data['cancellation_reason'];
        $transaction->save();

        Log::info($transaction);

        return $transaction;
    }

    public function withdrawLoyaltyPoints(array $data): LoyaltyPointsTransaction
    {
        $account = $this->getAccount($data);
        if ($account->balance < $data['points_amount']) {
            throw new Exception('Insufficient funds: ' . $data['points_amount'], 400);
        }

        $transaction = LoyaltyPointsTransaction::create([
            'account_id' => $account->id,
            'points_rule' => 'withdraw',
            'points_amount' => -$data['points_amount'],
            'description' => $data['description'],
        ]);
        Log::info($transaction);

        return $transaction;
    }
}
