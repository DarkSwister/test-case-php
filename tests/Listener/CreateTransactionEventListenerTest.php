<?php

namespace Listener;

use App\Entity\Account;
use App\Entity\Client;
use App\Event\AccountBalanceUpdated;
use App\Exceptions\TransactionCreationFailed;
use App\Listener\CreateTransactionEventListener;
use App\Repository\AccountRepository;
use App\Repository\TransactionRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateTransactionEventListenerTest extends TestCase
{
    private CreateTransactionEventListener $eventListener;
    private AccountRepository|MockObject $accountRepositoryMock;
    private TransactionRepository|MockObject $transactionRepositoryMock;

    public function setUp(): void
    {
        $this->accountRepositoryMock = $this->createMock(AccountRepository::class);
        $this->transactionRepositoryMock = $this->createMock(TransactionRepository::class);

        $this->eventListener = new CreateTransactionEventListener(
            $this->accountRepositoryMock,
            $this->transactionRepositoryMock
        );
    }

    public function testOnHandle()
    {
        $client = new Client();

        $sourceAccount = new Account($client, 'USD', 5000);
        $targetAccount = new Account($client, 'USD', 70000);
        $this->accountRepositoryMock
            ->expects($this->exactly(2))
            ->method('findAccountOrFail')
            ->willReturnOnConsecutiveCalls($sourceAccount, $targetAccount);

        $this->transactionRepositoryMock
            ->expects($this->exactly(2))
            ->method('add');
        $sourceAccount->setId(1);
        $targetAccount->setId(2);
        $sourceAccountId = $sourceAccount->getId();
        $targetAccountId = $targetAccount->getId();

        $event = new AccountBalanceUpdated(
            $sourceAccountId,
            $targetAccountId,
            10000
        );

        $this->eventListener->handle($event);
    }
}