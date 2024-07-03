<?php

declare(strict_types=1);

namespace App\Http\Requests\LoyaltyPoints;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $cancellation_reason
 * @property-read int $transaction_id
 */
class CancelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cancellation_reason' => 'required|string|max:255',
            'transaction_id' => 'required|integer',
        ];
    }
}
