<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class LoyaltyPointsWithdrawRequest extends FormRequest
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
        Log::info('Withdraw loyalty points transaction input: ' . $this);
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
            'points_amount' => 'required|integer|gt:0',
            'description' => 'required|string',
        ];
    }
}
