<?php

namespace App\CurrencyConverter\Factory;

use App\CurrencyConverter\Adapters\ApiCurrencyConverter;
use App\CurrencyConverter\Adapters\DbCurrencyConverter;
use App\CurrencyConverter\Contract\CurrencyConverterInterface;
use App\Repository\CurrencyRateRepository;
use Brick\Money\Currency;
use Brick\Money\Money;

final  readonly  class CurrencyConverterFactory implements CurrencyConverterInterface
{

    public function __construct(
        private DbCurrencyConverter $dbCurrencyConverter,
        private ApiCurrencyConverter $apiCurrencyConverter,
        private CurrencyRateRepository $currencyRates
    ) {}

    public function convert(Money $amount, Currency $targetCurrency): Money
    {

        $currencyRate = $this->currencyRates->findPair(
            $amount->getCurrency()->getCurrencyCode(),
            $targetCurrency->getCurrencyCode()
        );
        if (! $currencyRate || $currencyRate->isOlderThanDay()) {
            return $this->apiCurrencyConverter->convert($amount, $targetCurrency);
        }

        return $this->dbCurrencyConverter->convert($amount, $targetCurrency);
    }
}