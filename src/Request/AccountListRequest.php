<?php

declare(strict_types=1);

namespace App\Request;

use Webmozart\Assert\Assert;

final class AccountListRequest
{
    private int $clientId;
    private int $limit;
    private int $offset;


    public function __construct(
        int $clientId,  ?int $limit, ?int $offset)
    {
        Assert::integer($clientId);
        Assert::greaterThan($clientId,0);
        Assert::nullOrGreaterThanEq($limit,0);
        Assert::nullOrGreaterThanEq($offset,0);

        $this->clientId = $clientId;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function clientId(): int
    {
        return $this->clientId;
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
