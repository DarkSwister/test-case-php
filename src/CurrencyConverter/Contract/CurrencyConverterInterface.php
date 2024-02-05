<?php

namespace App\CurrencyConverter\Contract;

use Brick\Money\Currency;
use Brick\Money\Money;

interface CurrencyConverterInterface
{
    public function convert(Money $amount, Currency $targetCurrency): Money;
}