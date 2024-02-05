<?php

declare(strict_types=1);

namespace App\Entity\DTO;

final class MoneyAmountTransferDTO
{
    private int $sourceAccountId;
    private int $targetAccountId;
    private int $amount;

    public function __construct(int $sourceAccountId, int $targetAccountId, int $amount)
    {
        $this->sourceAccountId = $sourceAccountId;
        $this->targetAccountId = $targetAccountId;
        $this->amount = $amount;
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
}
