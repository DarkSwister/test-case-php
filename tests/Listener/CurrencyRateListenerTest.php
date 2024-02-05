<?php

namespace Listener;

use App\Entity\DTO\CurrencyRateDTO;
use App\Event\CurrencyRateReceiveEvent;
use App\Exceptions\CurrencyUpdateFailException;
use App\Listener\CurrencyRateListener;
use App\Service\CurrencyRateService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CurrencyRateListenerTest extends TestCase
{
    private CurrencyRateListener $currencyRateListener;
    private CurrencyRateService|MockObject $currencyRateServiceMock;

    protected function setUp(): void
    {
        $this->currencyRateServiceMock = $this->createMock(CurrencyRateService::class);
        $this->currencyRateListener = new CurrencyRateListener($this->currencyRateServiceMock);
    }

    public function testHandleSuccessfullyUpdatesCurrencyRate(): void
    {
        $event = new CurrencyRateReceiveEvent('USD', 'EUR', 1.2, new \DateTimeImmutable('2022-01-22'));

        $this->currencyRateServiceMock
            ->expects($this->once())
            ->method('updateCurrencyRatePair')
            ->with(
                $this->isInstanceOf(CurrencyRateDTO::class)
            );

        $this->currencyRateListener->handle($event);
    }

    public function testHandleThrowsExceptionOnFailure(): void
    {
        $event = new CurrencyRateReceiveEvent('USD', 'EUR', 1.2, new \DateTimeImmutable('2022-01-22'));

        $this->currencyRateServiceMock
            ->expects($this->once())
            ->method('updateCurrencyRatePair')
            ->willThrowException(new \Exception('Something went wrong'));

        $this->expectException(CurrencyUpdateFailException::class);
        $this->expectExceptionMessage('Currency update failed');

        $this->currencyRateListener->handle($event);
    }
}