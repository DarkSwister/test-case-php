<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;
use Throwable;

final class AccountDataFetchFailed extends RuntimeException
{
    public static function from(Throwable $exception): self
    {
        return new self('Account data fetch failed.', 0, $exception);
    }
}
