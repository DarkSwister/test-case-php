<?php

declare(strict_types=1);

namespace App\Request;

use Webmozart\Assert\Assert;

final class TransactionListRequest
{
    private int $accountId;
    private int $limit;
    private int $offset;


    public function __construct(int $accountId,  ?int $limit, ?int $offset)
    {
        Assert::integer($accountId);
        Assert::greaterThan($accountId,0);
        Assert::nullOrGreaterThanEq($limit,0);
        Assert::nullOrGreaterThanEq($offset,0);

        $this->accountId = $accountId;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function accountId(): int
    {
        return $this->accountId;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function offset(): int
    {
        return $this->offset;
    }
}
