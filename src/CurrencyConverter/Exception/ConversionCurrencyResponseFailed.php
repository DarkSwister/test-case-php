<?php

namespace App\CurrencyConverter\Exception;

use RuntimeException;
use Throwable;

final class ConversionCurrencyResponseFailed extends RuntimeException
{
    public static function from(Throwable $exception): self
    {
        return new self('Convert currency response failed', 0, $exception);
    }
}