<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CancelLoyaltyPointsRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_id' => ['exists:App\Models\LoyaltyPointsTransaction,id'],
            'cancellation_reason' => ['required', 'string'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'cancellation_reason.required' => 'Cancellation reason is not specified',
        ];
    }
}
