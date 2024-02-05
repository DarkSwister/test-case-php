<?php

declare(strict_types=1);

namespace CurrencyConverter;

use App\CurrencyConverter\Adapters\ApiCurrencyConverter;
use App\CurrencyConverter\Client\CurrencyHttpClient;
use App\CurrencyConverter\Exception\ConversionRequestFailed;
use App\CurrencyConverter\Response\ConvertCurrencyResponse;
use Brick\Money\Currency;
use Brick\Money\Money;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

final class ApiCurrencyConverterTest extends TestCase
{
    private CurrencyHttpClient|MockObject $httpClient;
    private EventDispatcherInterface|MockObject $eventDispatcher;
    private ApiCurrencyConverter $converter;

    public function setUp(): void
    {
        $this->converter = new ApiCurrencyConverter(
            $this->httpClient = $this->createMock(CurrencyHttpClient::class),
            $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class)
        );
    }

    public function testConvert(): void
    {
        $amount = Money::of('10', 'EUR');
        $currency = Currency::of('USD');

        $response = new ConvertCurrencyResponse(
            [
                'success' => true,
                'query' => [
                    'from' => 'EUR',
                    'to' => 'USD',
                    'amount' => '10',
                ],
                'info' => [
                    'timestamp' => 1627308000,
                    'rate' => 1.1875,
                ],
                'historical' => true,
                'date' => '2021-07-26',
                'result' => 11.875,
            ]
        );

        $this->httpClient
            ->expects($this->once())
            ->method('convert')
            ->with($amount->getCurrency()->getCurrencyCode(), $currency->getCurrencyCode(), (string) $amount->getAmount())
            ->willReturn($response);

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch');

        $testResult = Money::of('11.88', 'USD');
        $result = $this->converter->convert($amount, $currency);

        $this->assertEquals($testResult, $result);
    }

    public function testConvertThrowsUnsuccessfulException(): void
    {
        $this->expectException(ConversionRequestFailed::class);

        $amount = Money::of('10', 'EUR');
        $currency = Currency::of('USD');

        $response = new ConvertCurrencyResponse([
                'success' => false,
        ]);

        $this->httpClient
            ->expects($this->once())
            ->method('convert')
            ->with($amount->getCurrency()->getCurrencyCode(), $currency->getCurrencyCode(), (string) $amount->getAmount())
            ->willReturn($response);

        $this->eventDispatcher
            ->expects($this->never())
            ->method('dispatch');

        $this->converter->convert($amount, $currency);
    }
}
