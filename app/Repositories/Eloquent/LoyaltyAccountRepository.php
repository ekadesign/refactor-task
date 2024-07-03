<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\LoyaltyAccount;
use App\Repositories\LoyaltyAccountRepository as Repository;
use App\ValueObjects\LoyaltyAccountNaturalId;

class LoyaltyAccountRepository implements Repository
{
    public function findByNaturalId(LoyaltyAccountNaturalId $id): ?LoyaltyAccount
    {
        /** @var LoyaltyAccount|null $account */
        $account = LoyaltyAccount::query()->where($id->getType(), '=', $id->getCode())->first();

        return $account;
    }
}
