<?php

declare(strict_types=1);

namespace App\Request;

use Webmozart\Assert\Assert;

final readonly class MoneyTransferRequest
{
    private int $sourceAccountId;
    private int $targetAccountId;
    private int $amount;


    public function __construct(
        array $data
    )
    {
        Assert::keyExists($data, 'sourceAccountId');
        Assert::keyExists($data, 'targetAccountId');
        Assert::keyExists($data, 'amount');

        $sourceAccountId = $data['sourceAccountId'];
        $targetAccountId = $data['targetAccountId'];
        $amount = $data['amount'];

        Assert::integer($sourceAccountId);
        Assert::greaterThan($sourceAccountId, 0);
        Assert::integer($targetAccountId);
        Assert::greaterThan($targetAccountId, 0);
        Assert::notSame($sourceAccountId, $targetAccountId, 'Target account must be different from source account');
        Assert::numeric($amount);
        Assert::greaterThan($amount, 0);

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
