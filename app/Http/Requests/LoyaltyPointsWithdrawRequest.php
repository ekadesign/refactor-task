<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoyaltyPointsWithdrawRequest extends FormRequest
{
    use LoyaltyPointsRequestTrait;

    public function rules(): array
    {
        return [
            'account_type' => ['required', 'in:phone,card,email'],
            'account_id' => ['required', "exists:App\Models\LoyaltyAccount,$this->account_type,active,1"],
            'points_amount' => ['required', 'numeric', 'gt:0'],
            'description' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'account_type.in' => 'Wrong account parameters',
            'account_id.exists' => 'Account is not found',
            'points_amount.gt' => 'Wrong loyalty points amount',
        ];

    }
}
