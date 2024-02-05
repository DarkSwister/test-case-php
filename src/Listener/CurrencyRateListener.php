<?php

namespace App\Listener;

use App\Entity\DTO\CurrencyRateDTO;
use App\Event\CurrencyRateReceiveEvent;
use App\Exceptions\CurrencyUpdateFailException;
use App\Service\CurrencyRateService;
use Throwable;

final readonly class CurrencyRateListener
{
    public function __construct(private CurrencyRateService $currencyRateService)
    {
    }

    public function handle(CurrencyRateReceiveEvent $event):void {
        try {
            $command = new CurrencyRateDTO(
                $event->getBaseCurrency(),
                $event->getTargetCurrency(),
                $event->getRate(),
                $event->getDate()
            );

            $this->currencyRateService->updateCurrencyRatePair($command);
        } catch (Throwable $exception) {
            throw new CurrencyUpdateFailException(
                message: 'Currency update failed',
            );
        }
    }
}