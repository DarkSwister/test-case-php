<?php

namespace App\Exceptions;

use RuntimeException;

final class CurrencyRateNotFound extends RuntimeException
{
    public static function byCurrencyPair(string $baseCurrency, string $targetCurrency): self
    {
        return new self(sprintf('Currency rate for %s/%s not found', $baseCurrency, $targetCurrency));
    }
}