<?php

declare(strict_types=1);

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class AccountBalanceUpdated extends Event
{
    private int $sourceAccountId;
    private int $targetAccountId;
    private int $amount;
    private ?int $convertedAmount;

    public function __construct(int $sourceAccountId, int $targetAccountId, int $amount, int $convertedAmount = null)
    {
        $this->sourceAccountId = $sourceAccountId;
        $this->targetAccountId = $targetAccountId;
        $this->amount = $amount;
        $this->convertedAmount = $convertedAmount;
    }

    public function sourceAccountId(): int
    {
        return $this->sourceAccountId;
    }

    public function targetAccountId(): int
    {
        return $this->targetAccountId;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function convertedAmount(): ?int
    {
        return $this->convertedAmount;
    }
}
