<?php

declare(strict_types=1);

namespace App\Http\Requests\LoyaltyPoints;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $account_type
 * @property-read string $account_id
 * @property-read string $loyalty_points_rule
 * @property-read string $description
 * @property-read string $payment_id
 * @property-read float $payment_amount
 * @property-read int $payment_time
 */
class DepositRequest extends FormRequest
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
            'loyalty_points_rule' => 'required|exists:loyalty_points_rule,points_rule',
            'description' => 'required|string|nax:255',
            'payment_id' => 'required|string|max:255"',
            'payment_amount' => 'required|numeric',
            'payment_time' => 'required|integer',
        ];
    }
}
