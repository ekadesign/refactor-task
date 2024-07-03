<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;

final class LoyaltyAccountNaturalId
{
    private const ALLOWED_TYPES = ['phone', 'card', 'email'];

    public function __construct(
        private string $type,
        private string $code,
    ) {
        if (static::isValidaType($this->type)) {
            throw new InvalidArgumentException('Invalid $type argument');
        }
    }

    public static function isValidaType(string$type ): bool
    {
        return \in_array($type, self::ALLOWED_TYPES, true);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
