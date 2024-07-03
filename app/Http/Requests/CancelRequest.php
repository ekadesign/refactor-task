<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CancelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'transaction_id' => 'required|integer',
            'cancellation_reason' => 'required|string',
        ];
    }
}
