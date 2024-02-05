<?php

namespace Request;

use App\Request\MoneyTransferRequest;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class MoneyTransferRequestTest extends TestCase
{
    public function testConstructorAndGetAccountId(): void
    {
        $data =[
            'sourceAccountId'=>1,
            'targetAccountId'=>2,
            'amount'=>10
        ];

        $request = new MoneyTransferRequest($data);

        $this->assertEquals($data['targetAccountId'], $request->targetAccountId());
        $this->assertEquals($data['sourceAccountId'], $request->sourceAccountId());
        $this->assertEquals($data['amount'], $request->amount());

    }

}