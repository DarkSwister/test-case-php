<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class AccountNotFound extends RuntimeException
{
    public static function byId(int $id): self
    {
        return new self(sprintf('Account with id %s not found', $id));
    }
}
