<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoyaltyPointsDepositRequest extends FormRequest
{
    use LoyaltyPointsRequestTrait;

    public function rules(): array
    {
        return [
            'account_type' => ['required', 'in:phone,card,email'],
            'account_id' => ['required', "exists:App\Models\LoyaltyAccount,$this->account_type,active,1"],
            'loyalty_points_rule' => ['required'],
            'description' => ['required'],
            'payment_id' => ['required'],
            'payment_amount' => ['required'],
            'payment_time' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'account_type.in' => 'Wrong account parameters'
        ];
    }
}
