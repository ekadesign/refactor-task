<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class LoyaltyPointsDepositRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        Log::info('Deposit transaction input: ' . $this);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_type' => 'required|in:phone,card,email',
            'account_id' => 'required|exists:loyalty_account,' . $this->account_type,
            'loyalty_points_rule' => 'required|exists:loyalty_points_rule,points_rule',
            'description' => 'required|string',
            'payment_id' => 'required|string',
            'payment_amount' => 'required|numeric',
            'payment_time' => 'required|integer',
        ];
    }
}
