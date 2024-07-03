<?php

namespace App\Repositories;

interface LoyaltyAccountRepositoryInterface
{
    public function findByTypeAndId($type, $id);
}
