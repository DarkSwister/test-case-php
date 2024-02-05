<?php

namespace App\Entity;

use App\Repository\CurrencyRateRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRateRepository::class)]
class CurrencyRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private string $base;

    #[ORM\Column(type: Types::STRING)]
    private string $target;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $rate;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, updatable: true)]
    private DateTimeImmutable $date;

    public function __construct(string $base, string $target, float $rate, DateTimeImmutable $date)
    {
        $this->base = $base;
        $this->target = $target;
        $this->rate = $rate;
        $this->date = $date;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function setBase(string $base): void
    {
        $this->base = $base;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function setTarget(string $target): void
    {
        $this->target = $target;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }
    public function isOlderThanDay(): bool
    {
        return $this->date->diff(new DateTimeImmutable())->days > 1;
    }

}
