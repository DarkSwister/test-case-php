<?php

declare(strict_types=1);

namespace App\Entity\Collection;

use App\Entity\Transaction;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Override;
use Traversable;

final readonly class TransactionCollection implements Countable, IteratorAggregate
{
    /** @var Transaction[] */
    private array $transactions;

    private int $count;

    /**
     * @param Countable&IteratorAggregate<int, Transaction> $transaction
     */
    public function __construct(Countable&IteratorAggregate $transaction)
    {
        $this->transactions = iterator_to_array($transaction);
        $this->count = count($transaction);
    }

    #[Override]
    public function count(): int
    {
        return $this->count;
    }

    /**
     * @return Traversable<int, Transaction>
     */
    #[Override]
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->transactions);
    }

    public function getItems(): array
    {
        return $this->transactions;
    }
}
