<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'account_type' => 'required|string|in:phone,card,email',
            'account_id' => 'required|string',
            'points_amount' => 'required|numeric|min:1',
            'description' => 'required|string',
        ];
    }
}
