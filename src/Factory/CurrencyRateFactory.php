<?php
declare(strict_types=1);

namespace App\Application\Factory;

use App\Entity\CurrencyRate;
use DateTimeImmutable;

final class CurrencyRateFactory
{
    public static function create(
        string $base,
        string $target,
        float $rate,
        DateTimeImmutable $date
    ): CurrencyRate {
        return new CurrencyRate($base, $target, $rate, $date);
    }
}