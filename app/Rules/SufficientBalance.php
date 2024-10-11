<?php

namespace App\Rules;

use App\Models\LoyaltyAccount;
use Illuminate\Contracts\Validation\Rule;

class SufficientBalance implements Rule
{
    private string $accountType;
    private int $accountId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $accountId, string $accountType)
    {
        $this->accountId = $accountId;
        $this->accountType = $accountType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $account = LoyaltyAccount::query()->where($this->accountType, $this->accountId)->first();
        return $account && $account->getBalance() > $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Insufficient funds';
    }
}
