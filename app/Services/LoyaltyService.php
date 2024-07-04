<?php

namespace App\Services;

use App\DataTransfers\Loyalty\CancelDTO;
use App\DataTransfers\Loyalty\DepositDTO;
use App\DataTransfers\Loyalty\WithdrawDTO;
use App\Mail\LoyaltyPointsReceived;
use App\Models\LoyaltyAccount;
use App\Models\LoyaltyPointsTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoyaltyService
{
    public function deposit(DepositDTO $data)
    {
        Log::info('Deposit transaction input: ' . print_r($data, true));

        $type = $data['account_type'];
        $id = $data['account_id'];

        $account = $this->findAccount($type, $id);
        $this->ensureAccountIsActive($account);

        $transaction = LoyaltyPointsTransaction::performPaymentLoyaltyPoints(
            $account->id,
            $data['loyalty_points_rule'],
            $data['description'],
            $data['payment_id'],
            $data['payment_amount'],
            $data['payment_time']
        );

        $this->sendNotifications($account, $transaction);

        return $transaction;
    }

    public function cancel(CancelDTO $data)
    {
        $reason = $data['cancellation_reason'];
        if (empty($reason)) {
            throw new \InvalidArgumentException('Cancellation reason is not specified');
        }

        $transaction = LoyaltyPointsTransaction::where('id', '=', $data['transaction_id'])
            ->where('canceled', '=', 0)
            ->firstOrFail();

        $transaction->canceled = time();
        $transaction->cancellation_reason = $reason;
        $transaction->save();
    }

    public function withdraw(WithdrawDTO $data)
    {
        Log::info('Withdraw loyalty points transaction input: ' . print_r($data, true));

        $type = $data['account_type'];
        $id = $data['account_id'];

        $account = $this->findAccount($type, $id);
        $this->ensureAccountIsActive($account);
        $this->validatePointsAmount($data['points_amount']);
        $this->ensureSufficientFunds($account, $data['points_amount']);

        return LoyaltyPointsTransaction::withdrawLoyaltyPoints(
            $account->id,
            $data['points_amount'],
            $data['description']
        );
    }

    protected function findAccount($type, $id)
    {
        if (!in_array($type, ['phone', 'card', 'email']) || empty($id)) {
            throw new \InvalidArgumentException('Wrong account parameters');
        }

        $account = LoyaltyAccount::where($type, '=', $id)->first();
        if (!$account) {
            throw new \Exception('Account is not found');
        }

        return $account;
    }

    protected function ensureAccountIsActive($account)
    {
        if (!$account->active) {
            throw new \Exception('Account is not active');
        }
    }

    protected function validatePointsAmount($amount)
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Wrong loyalty points amount');
        }
    }

    protected function ensureSufficientFunds($account, $amount)
    {
        if ($account->getBalance() < $amount) {
            throw new \Exception('Insufficient funds');
        }
    }

    protected function sendNotifications($account, $transaction)
    {
        if (!empty($account->email) && $account->email_notification) {
            Mail::to($account)->send(new LoyaltyPointsReceived($transaction->points_amount, $account->getBalance()));
        }

        if (!empty($account->phone) && $account->phone_notification) {
            Log::info('You received ' . $transaction->points_amount . ' Your balance ' . $account->getBalance());
        }
    }
}
