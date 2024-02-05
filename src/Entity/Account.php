<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AccountRepository;
use Brick\Money\Currency;
use Brick\Money\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'payer', targetEntity: Transaction::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $transactions;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $balance = null;

    #[ORM\Column(type: Types::STRING, length: 6)]
    private ?string $currency = null;

    public function __construct(Client $client, string $currency, int $balance)
    {
        $this->client = $client;
        $this->currency = $currency;
        $this->balance = $balance;
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setSender($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getSender() === $this) {
                $transaction->setSender(null);
            }
        }

        return $this;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function fromBalanceTransformerMoneyAmount(): Money
    {
        return Money::of($this->getBalance(), Currency::of($this->getCurrency()))->dividedBy(100);
    }
}