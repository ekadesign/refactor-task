<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\SufficientBalance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WithdrawLoyaltyPointsRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
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
            'account_id' => ['required', 'exists:App\Models\LoyaltyAccount,id'],
            'account_type' => ['required', 'string', Rule::in(['email', 'phone', 'card'])],
            'description' => ['required', 'string'],
            'points_amount' => ['required', 'numeric', 'gt:0',
                new SufficientBalance(
                    $this->input('account_id'),
                    $this->input('account_type')
                ),
            ]
        ];
    }
}
