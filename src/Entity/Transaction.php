<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Account $sender = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Account $receiver = null;

    #[ORM\Column(type: Types::STRING)]
    private string $currency;

    #[ORM\Column(type: Types::INTEGER)]
    private int $amount;

    #[ORM\Column(type: Types::STRING, length: 10, nullable: false)]
    private string $type;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct(Account $sender, Account $receiver, int $amount, string $currency, string $type)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->type = $type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?Account
    {
        return $this->sender;
    }

    public function setSender(?Account $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getReceiver(): ?Account
    {
        return $this->receiver;
    }

    public function setReceiver(?Account $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
    
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }


}