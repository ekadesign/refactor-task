<?php

declare(strict_types=1);

namespace App\Http\Requests\LoyaltyPoints;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $account_type
 * @property-read string $account_id
 * @property-read float $points_amount
 * @property-read string $description
 */
class WithdrawRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_type' => 'required|in:phone,card,email',
            'account_id' => 'required|exists:loyalty_account,' . $this->account_type,
            'points_amount' => 'required|numeric',
            'description' => 'required|string|nax:255',
        ];
    }
}
