<?php

namespace App\Repositories;

use App\Models\LoyaltyAccount;

class LoyaltyAccountRepository implements LoyaltyAccountRepositoryInterface
{
    public function findByTypeAndId($type, $id)
    {
        return LoyaltyAccount::where($type, $id)->first();
    }
}
