<?php

namespace Request;

use App\Request\TransactionListRequest;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TransactiontListRequestTest extends TestCase
{
    public function testConstructorAndGetAccountId(): void
    {
        $accountId = 1;
        $request = new TransactionListRequest($accountId, 0,  0);

        $this->assertEquals($accountId, $request->accountId());
    }

    public function testConstructorThrowsExceptionForInvalidAccountId(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new TransactionListRequest(-1,5,0);
    }
}