<?php

declare(strict_types=1);

namespace App\Listener;

use App\Entity\Enum\TransactionType;
use App\Event\AccountBalanceUpdated;
use App\Exceptions\TransactionCreationFailed;
use App\Factory\TransactionFactory;
use App\Repository\AccountRepository;
use App\Repository\TransactionRepository;
use Throwable;

final readonly class CreateTransactionEventListener
{
    public function __construct(private AccountRepository $accounts, private TransactionRepository $transactions)
    {
    }

    public function handle(AccountBalanceUpdated $event): void
    {
        try {
            $sourceAccount = $this->accounts->findAccountOrFail($event->sourceAccountId());
            $targetAccount = $this->accounts->findAccountOrFail($event->targetAccountId());
            $outgoingTransaction = TransactionFactory::create(
                $sourceAccount,
                $targetAccount,
                $event->amount(),
                $sourceAccount->getCurrency(),
                type: TransactionType::OUTGOING->value
            );

            $incomingTransaction = TransactionFactory::create(
                $sourceAccount,
                $targetAccount,
                $event->convertedAmount() ?: $event->amount(),
                $targetAccount->getCurrency(),
                type: TransactionType::INCOMING->value
            );

            $this->transactions->add($outgoingTransaction);
            $this->transactions->add($incomingTransaction);
        } catch (Throwable $exception) {
            throw TransactionCreationFailed::from($exception);
        }
    }


}
