<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\LoyaltyPointsDeposited;
use App\Mail\LoyaltyPointsReceived;
use Illuminate\Mail\Mailer;
use Psr\Log\LoggerInterface;

class LoyaltyPointsDepositedListener
{
    public function __construct(
        private LoggerInterface $logger,
        private Mailer $mailer,
    ) {
    }

    public function handle(LoyaltyPointsDeposited $event): void
    {
        $account = $event->getAccount();
        $transaction = $event->getTransaction();

        if ($account->email_notification && !empty($account->email)) {
            $this->mailer->to($event->getAccount())->queue(new LoyaltyPointsReceived(
                $event->getTransaction()->points_amount,
                $account->getBalance()
            ));
        }

        if ($account->phone_notification && !empty($account->phone)) {
            $this->logger->info(
                "You received {$transaction->points_amount} "
                . "Your balance {$account->getBalance()}"
            );
        }
    }
}
