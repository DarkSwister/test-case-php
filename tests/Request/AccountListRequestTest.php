<?php

namespace Request;

use App\Request\AccountListRequest;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
class AccountListRequestTest extends TestCase
{
    public function testConstructorAndGetClientId(): void
    {
        $clientId = 1;
        $request = new AccountListRequest($clientId, 0,  0);

        $this->assertEquals($clientId, $request->clientId());
    }

    public function testConstructorThrowsExceptionForInvalidClientId(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new AccountListRequest(-1,0,0);
    }
}