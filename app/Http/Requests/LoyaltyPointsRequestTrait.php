<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;

trait LoyaltyPointsRequestTrait
{
    public function authorize(): bool
    {
        // По идее здесь может быть проверка владения аккаунтом,
        // то есть $this->user()->id === $loyaltyAccount->user_id,
        // но я не нашел связи между таблицами loyalty_account и users

        return true;
    }

    public function prepareForValidation(): void
    {
        Log::info(static::class . ' input:', $this->only(array_keys($this->rules())));
    }

}
