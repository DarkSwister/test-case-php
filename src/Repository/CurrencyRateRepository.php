<?php

namespace App\Repository;

use App\Entity\CurrencyRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CurrencyRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyRate::class);
    }

    public function findPair(string $base, string $target): ?CurrencyRate
    {
        $qb = $this->createQueryBuilder('cr')
            ->where('cr.base = :base')
            ->setParameter('base', $base)
            ->andWhere('cr.target = :target')
            ->setParameter('target', $target)
            ->orderBy('cr.date', 'DESC')
            ->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function add(CurrencyRate $currencyRate): void
    {
        $this->_em->persist($currencyRate);
        $this->_em->flush();
    }

    public function update(CurrencyRate $currencyRate): void
    {
        $this->_em->persist($currencyRate);
        $this->_em->flush();
    }
}