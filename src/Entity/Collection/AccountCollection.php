<?php

declare(strict_types=1);

namespace App\Entity\Collection;

use App\Entity\Account;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Override;
use Traversable;

final readonly class AccountCollection implements Countable, IteratorAggregate
{
    private array $accounts;

    private int $count;

    /**
     * @param Countable&IteratorAggregate<int, Account> $accounts
     */
    public function __construct(Countable&IteratorAggregate $accounts)
    {
        $this->accounts = iterator_to_array($accounts);
        $this->count = count($accounts);
    }

    #[Override]
    public function count(): int
    {
        return $this->count;
    }

    /**
     * @return Traversable<int, Account>
     */
    #[Override]
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->accounts);
    }

    public function getItems(): array
    {
        return $this->accounts;
    }
}
