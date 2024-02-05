<?php

namespace App\Repository;

use App\Entity\Collection\TransactionCollection;
use App\Entity\Enum\TransactionType;
use App\Entity\Transaction;
use App\Entity\Util\SearchSorting;
use App\Entity\Util\SortingOrder;
use App\Entity\Util\TransactionSearchCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Throwable;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function findByCriteria(TransactionSearchCriteria $criteria): array
    {
        $builder = $this->_em->createQueryBuilder();
        $builder->select(select: 'transaction')->from(from: Transaction::class, alias: 'transaction');
        if (!is_null($criteria->accountId)) {
            $builder->andWhere(
                $builder->expr()->orX(
                    $builder->expr()->andX(
                        $builder->expr()->eq('transaction.sender', ':accountId'),
                        $builder->expr()->eq('transaction.type', ':outgoingType')
                    ),
                    $builder->expr()->andX(
                        $builder->expr()->eq('transaction.receiver', ':accountId'),
                        $builder->expr()->eq('transaction.type', ':incomingType')
                    )
                )
            );
            $builder->setParameter('accountId', $criteria->accountId, Types::INTEGER);
            $builder->setParameter('outgoingType', TransactionType::OUTGOING->value, Types::STRING);
            $builder->setParameter('incomingType', TransactionType::INCOMING->value, Types::STRING);

        }

        if (!is_null($criteria->sorting)) {
            $sortingFieldsMapping = [
                TransactionSearchCriteria::FIELD_CREATED_AT => 'transaction.createdAt',
            ];
            $builder->orderBy($this->buildOrderBy($criteria->sorting, $sortingFieldsMapping));
        }
        if ($criteria->pagination?->limit > 0) {
            $builder->setMaxResults($criteria->pagination?->limit);
        }
        if ($criteria->pagination?->offset > 0) {
            $builder->setFirstResult($criteria->pagination?->offset);
        }
        return $builder->getQuery()->getResult();
    }

    public function add(Transaction $transaction): void
    {
        $this->_em->persist($transaction);
        $this->_em->flush();
    }

    public function update(Transaction $transaction): void
    {
        $this->_em->persist($transaction);
        $this->_em->flush();
    }

    public function beginTransaction(): void
    {
        $this->_em->getConnection()->beginTransaction();
    }

    public function rollback(): void
    {
        $this->_em->getConnection()->rollBack();
    }

    public function commit(): void
    {
        $this->_em->getConnection()->commit();
    }

    /**
     * @param SearchSorting $searchSorting
     * @param array&array<string, string> $fieldsMapping
     * @return OrderBy
     */
    protected function buildOrderBy(SearchSorting $searchSorting, array $fieldsMapping): OrderBy
    {
        $sortingField = $fieldsMapping[$searchSorting->field] ?? throw new LogicException(
            message: sprintf('Sorting field "%s" is not supported.', $searchSorting->field),
        );

        return match ($searchSorting->order) {
            SortingOrder::ASC => $this->_em->getExpressionBuilder()->asc($sortingField),
            SortingOrder::DESC => $this->_em->getExpressionBuilder()->desc($sortingField),
            default => throw new LogicException(
                message: sprintf('Sorting order "%s" is not supported.', $searchSorting->order),
            ),
        };
    }
}