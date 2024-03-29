<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class AccountHasInsufficientFunds extends RuntimeException
{
    public static function byId(string $id): self
    {
        return new self(sprintf('Account with id %s has insufficient funds', $id));
    }
}
