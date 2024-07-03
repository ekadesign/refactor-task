<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'account_type' => 'required|string|in:phone,card,email',
            'account_id' => 'required|string',
            'loyalty_points_rule' => 'required|string',
            'description' => 'required|string',
            'payment_id' => 'required|string',
            'payment_amount' => 'required|numeric',
            'payment_time' => 'required|date',
        ];
    }
}
