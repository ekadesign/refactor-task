<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoyaltyPointsCancelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cancellation_reason' => ['required'],
            'transaction_id' => ['required', 'exists:App\Models\LoyaltyPointsTransaction,id,canceled,0'],
        ];
    }

    public function messages(): array
    {
        return [
            'cancellation_reason.required' => 'Cancellation reason is not specified'
        ];
    }
}
