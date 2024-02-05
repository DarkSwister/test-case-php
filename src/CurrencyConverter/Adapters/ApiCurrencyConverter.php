<?php

namespace App\CurrencyConverter\Adapters;


use App\CurrencyConverter\Contract\CurrencyConverterInterface;
use App\Event\CurrencyRateReceiveEvent;
use App\CurrencyConverter\Exception\ConversionClientResponseFailException;
use App\CurrencyConverter\Exception\ConversionRequestFailed;
use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;
use Psr\EventDispatcher\EventDispatcherInterface;
use App\CurrencyConverter\Client\CurrencyHttpClient as HttpClient;
use Throwable;

final readonly class ApiCurrencyConverter implements CurrencyConverterInterface
{
    public function __construct(private HttpClient $httpClient, private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function convert(Money $amount, Currency $targetCurrency): Money
    {

        try {
            $response = $this->httpClient->convert(
                $amount->getCurrency()->getCurrencyCode(),
                $targetCurrency->getCurrencyCode(),
                (string) $amount->getAmount()
            );
            if (! $response->isSuccess()) {
                throw new ConversionClientResponseFailException();
            }
            $this->eventDispatcher->dispatch(
                new CurrencyRateReceiveEvent(
                    $amount->getCurrency()->getCurrencyCode(),
                    $targetCurrency->getCurrencyCode(),
                    $response->rate(),
                    $response->conversionDate()
                )
            );

            return Money::of($response->result(), $targetCurrency, null, RoundingMode::HALF_UP);
        } catch (Throwable $exception) {

            throw ConversionRequestFailed::from($exception);
        }
    }
}