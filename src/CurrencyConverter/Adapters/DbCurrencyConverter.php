<?php

namespace App\CurrencyConverter\Adapters;

use App\CurrencyConverter\Contract\CurrencyConverterInterface;
use App\Exceptions\CurrencyRateNotFound;
use App\Repository\CurrencyRateRepository;
use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;

final readonly class DbCurrencyConverter implements CurrencyConverterInterface
{

    public function __construct(private CurrencyRateRepository $currencyRates)
    {
    }

    public function convert(Money $amount, Currency $targetCurrency): Money
    {
        $currencyRate = $this->currencyRates->findPair(
            $amount->getCurrency()->getCurrencyCode(),
            $targetCurrency->getCurrencyCode()
        );

        if (! $currencyRate) {
            throw new CurrencyRateNotFound('Currency rate not found.');
        }

        $convertedAmount = $amount->multipliedBy($currencyRate->getRate(), RoundingMode::HALF_UP);

        return Money::of($convertedAmount->getAmount(), $targetCurrency);
    }
}