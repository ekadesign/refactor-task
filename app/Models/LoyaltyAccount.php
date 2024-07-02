<?php

namespace App\Models;

use App\Mail\AccountActivated;
use App\Mail\AccountDeactivated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoyaltyAccount extends Model
{
    protected $table = 'loyalty_account';

    protected $fillable = [
        'phone',
        'card',
        'email',
        'email_notification',
        'phone_notification',
        'active',
    ];

    public function loyaltyPointsTransactions(): HasMany
    {
        return $this->hasMany(LoyaltyPointsTransaction::class);
    }

    public function getBalanceAttribute(): float
    {
        return $this->loyaltyPointsTransactions()
            ->withoutCancelled()
            ->sum('points_amount');
    }

    public function notify(): void
    {
        if ($this->email !== '' && $this->email_notification) {
            if ($this->active) {
                Mail::to($this)->send(new AccountActivated($this->getBalance()));
            } else {
                Mail::to($this)->send(new AccountDeactivated());
            }
        }

        if ($this->phone !== '' && $this->phone_notification) {
            // instead SMS component
            Log::info('Account: phone: ' . $this->phone . ' ' . ($this->active ? 'Activated' : 'Deactivated'));
        }
    }
}
