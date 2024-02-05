<?php

namespace App\Service;

use App\Application\Factory\CurrencyRateFactory;
use App\Entity\DTO\CurrencyRateDTO;
use App\Repository\CurrencyRateRepository;

readonly class CurrencyRateService
{
    public function __construct(private CurrencyRateRepository $currencyRateRepo)
    {
    }

    public function updateCurrencyRatePair(CurrencyRateDTO $dto): void
    {
        $currencyRatePair = $this->currencyRateRepo->findPair(
            $dto->baseCurrency(),
            $dto->targetCurrency()
        );

        if ($currencyRatePair) {
            $currencyRatePair->setRate($dto->rate());
            $currencyRatePair->setDate($dto->conversionDate());

            $this->currencyRateRepo->update($currencyRatePair);

            return;
        }

        $this->createCurrencyRatePair($dto);
    }

    public function createCurrencyRatePair(CurrencyRateDTO $dto): void
    {
        $this->currencyRateRepo->add(
            CurrencyRateFactory::create(
                $dto->baseCurrency(),
                $dto->targetCurrency(),
                $dto->rate(),
                $dto->conversionDate()
            ));
    }
}