<?php

namespace App\Event;

use DateTimeImmutable;

final readonly class CurrencyRateReceiveEvent
{
    public function __construct(private string $baseCurrency, private string $targetCurrency, private float $rate, private DateTimeImmutable $date )
    {
    }

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    public function getTargetCurrency(): string
    {
        return $this->targetCurrency;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}